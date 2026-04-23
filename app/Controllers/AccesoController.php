<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsuarioModel;
use App\Models\PersonaModel;
use App\Models\ContactoModel;
use App\Models\TipoContactoModel;
use App\Models\RolModel;
use App\Models\AlumnoModel;
use App\Libraries\Mailer;
use Config\Database;

class AccesoController extends BaseController
{
    protected $usuarioModel;
    protected $personaModel;
    protected $contactoModel;
    protected $tipoContactoModel;
    protected $rolModel;
    protected $alumnoModel;

    public function __construct()
    {
        $this->usuarioModel      = new UsuarioModel();
        $this->personaModel      = new PersonaModel();
        $this->contactoModel     = new ContactoModel();
        $this->tipoContactoModel = new TipoContactoModel();
        $this->rolModel          = new RolModel();
        $this->alumnoModel       = new AlumnoModel();
    }

    public function login()
    {
        return view('acceso/login', [
            'title' => 'Iniciar sesión'
        ]);
    }

    public function autenticar()
    {
        $rules = [
            'username' => 'required|min_length[3]',
            'password' => 'required|min_length[4]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('toast_error', $this->validator->getErrors());
        }

        $username = trim((string) $this->request->getPost('username'));
        $password = (string) $this->request->getPost('password');

        $usuario = $this->usuarioModel
            ->select('
            tbl_ope_usuario.*,
            tbl_rel_persona.nombre,
            tbl_rel_persona.apellido_paterno,
            tbl_rel_persona.apellido_materno,
            tbl_cat_rol.nombre_rol,
            tbl_rel_image.ruta_archivo as imagen_perfil
        ')
            ->join('tbl_rel_persona', 'tbl_rel_persona.id_persona = tbl_ope_usuario.fk_persona', 'left')
            ->join('tbl_cat_rol', 'tbl_cat_rol.id_rol = tbl_ope_usuario.fk_rol', 'left')
            ->join('tbl_rel_image', 'tbl_rel_image.id_image = tbl_ope_usuario.fk_image_perfil', 'left')
            ->where('tbl_ope_usuario.username', $username)
            ->first();

        if (!$usuario) {
            return redirect()->back()
                ->withInput()
                ->with('toast_error', 'El usuario no existe');
        }

        if (!password_verify($password, $usuario['password'])) {
            return redirect()->back()
                ->withInput()
                ->with('toast_error', 'La contraseña es incorrecta');
        }

        $nombreCompleto = trim(
            ($usuario['nombre'] ?? '') . ' ' .
                ($usuario['apellido_paterno'] ?? '') . ' ' .
                ($usuario['apellido_materno'] ?? '')
        );

        $sessionData = [
            'id_usuario'      => $usuario['id_usuario'],
            'fk_persona'      => $usuario['fk_persona'],
            'fk_rol'          => $usuario['fk_rol'],
            'username'        => $usuario['username'],
            'nombre_completo' => $nombreCompleto ?: $usuario['username'],
            'nombre_rol'      => $usuario['nombre_rol'] ?? 'Usuario',
            'imagen_perfil'   => $usuario['imagen_perfil'] ?? null,
            'tema_oscuro'     => $usuario['tema_oscuro'],
            'isLoggedIn'      => true,
        ];

        session()->set($sessionData);

        return redirect()->to('/')
            ->with('toast_success', 'Bienvenido de nuevo, ' . ($sessionData['nombre_completo']));
    }

    public function registro()
    {
        return view('acceso/registro', [
            'title' => 'Crear cuenta'
        ]);
    }

    public function guardarRegistro()
    {
        $rules = [
            'nombre'            => 'required|min_length[2]|max_length[100]',
            'apellido_paterno'  => 'required|min_length[2]|max_length[100]',
            'apellido_materno'  => 'permit_empty|max_length[100]',
            'correo'            => 'required|valid_email|max_length[150]',
            'username'          => 'required|min_length[4]|max_length[50]',
            'password'          => 'required|min_length[6]',
            'password_confirm'  => 'required|matches[password]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('toast_error', $this->validator->getErrors());
        }

        $nombre           = trim((string) $this->request->getPost('nombre'));
        $apellidoPaterno  = trim((string) $this->request->getPost('apellido_paterno'));
        $apellidoMaterno  = trim((string) $this->request->getPost('apellido_materno'));
        $correo           = trim((string) $this->request->getPost('correo'));
        $username         = trim((string) $this->request->getPost('username'));
        $password         = (string) $this->request->getPost('password');

        $usuarioExistente = $this->usuarioModel->where('username', $username)->first();
        if ($usuarioExistente) {
            return redirect()->back()
                ->withInput()
                ->with('toast_error', 'El nombre de usuario ya está en uso');
        }

        $correoExistente = $this->contactoModel
            ->where('valor_contacto', $correo)
            ->first();

        if ($correoExistente) {
            return redirect()->back()
                ->withInput()
                ->with('toast_error', 'El correo ya está registrado');
        }

        $tipoCorreo = $this->tipoContactoModel
            ->where('descripcion', 'Correo')
            ->first();

        if (!$tipoCorreo) {
            return redirect()->back()
                ->withInput()
                ->with('toast_error', 'No existe el tipo de contacto "Correo" en el catálogo');
        }

        $rolAlumno = $this->rolModel
            ->where('nombre_rol', 'Alumno')
            ->first();

        if (!$rolAlumno) {
            return redirect()->back()
                ->withInput()
                ->with('toast_error', 'No existe el rol "Alumno" en el catálogo');
        }

        $db = Database::connect();
        $db->transBegin();

        try {
            $this->personaModel->insert([
                'nombre'            => $nombre,
                'apellido_paterno'  => $apellidoPaterno,
                'apellido_materno'  => $apellidoMaterno ?: null,
            ]);

            $idPersona = $this->personaModel->getInsertID();

            $this->contactoModel->insert([
                'fk_persona'        => $idPersona,
                'fk_tipocontacto'   => $tipoCorreo['id_tipocontacto'],
                'valor_contacto'    => $correo,
                'es_verificado'     => 0,
            ]);

            $this->usuarioModel->insert([
                'fk_persona'        => $idPersona,
                'fk_rol'            => $rolAlumno['id_rol'],
                'fk_image_perfil'   => null,
                'username'          => $username,
                'password'          => password_hash($password, PASSWORD_DEFAULT),
                'tema_oscuro'       => 0,
            ]);

            $this->alumnoModel->insert([
                'fk_persona' => $idPersona,
                'matricula'  => 'ALU-' . date('YmdHis') . '-' . random_int(100, 999),
            ]);

            if ($db->transStatus() === false) {
                $db->transRollback();
                return redirect()->back()
                    ->withInput()
                    ->with('toast_error', 'No fue posible registrar al usuario');
            }

            $db->transCommit();

            return redirect()->to('/acceso/login')
                ->with('toast_success', 'Tu cuenta fue creada correctamente. Ahora puedes iniciar sesión.');
        } catch (\Throwable $e) {
            $db->transRollback();

            return redirect()->back()
                ->withInput()
                ->with('toast_error', 'Ocurrió un error al registrar el usuario: ' . $e->getMessage());
        }
    }

    public function recuperar()
    {
        return view('acceso/recuperar', [
            'title' => 'Recuperar contraseña'
        ]);
    }

    public function enviarRecuperacion()
    {
        $rules = [
            'correo' => 'required|valid_email|max_length[150]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('toast_error', $this->validator->getErrors());
        }

        $correo = trim((string) $this->request->getPost('correo'));

        $tipoCorreo = $this->tipoContactoModel
            ->where('descripcion', 'Correo')
            ->first();

        if (!$tipoCorreo) {
            return redirect()->back()
                ->withInput()
                ->with('toast_error', 'No existe el tipo de contacto "Correo" en el catálogo');
        }

        $contacto = $this->contactoModel
            ->where('fk_tipocontacto', $tipoCorreo['id_tipocontacto'])
            ->where('valor_contacto', $correo)
            ->first();

        if (!$contacto) {
            return redirect()->back()
                ->withInput()
                ->with('toast_error', 'No existe una cuenta asociada a ese correo');
        }

        $usuario = $this->usuarioModel
            ->where('fk_persona', $contacto['fk_persona'])
            ->first();

        if (!$usuario) {
            return redirect()->back()
                ->withInput()
                ->with('toast_error', 'No existe un usuario asociado a ese correo');
        }

        $persona = $this->personaModel->find($contacto['fk_persona']);
        $nombreCompleto = trim(($persona['nombre'] ?? '') . ' ' . ($persona['apellido_paterno'] ?? ''));

        $codigo = (string) random_int(100000, 999999);

        session()->set('reset_password', [
            'fk_persona'  => $contacto['fk_persona'],
            'id_usuario'  => $usuario['id_usuario'],
            'correo'      => $correo,
            'codigo'      => $codigo,
            'expires_at'  => time() + (15 * 60),
        ]);

        $mailer = new Mailer();

        $html = '
            <div style="font-family:Arial,sans-serif;line-height:1.6;color:#222;">
                <h2>Recuperación de contraseña</h2>
                <p>Hola ' . esc($nombreCompleto ?: $usuario['username']) . ',</p>
                <p>Recibimos una solicitud para restablecer tu contraseña.</p>
                <p>Tu código de verificación es:</p>
                <div style="font-size:32px;font-weight:bold;letter-spacing:4px;margin:20px 0;color:#0d6efd;">
                    ' . esc($codigo) . '
                </div>
                <p>Este código expira en 15 minutos.</p>
                <p>Si tú no realizaste esta solicitud, puedes ignorar este mensaje.</p>
            </div>
        ';

        $send = $mailer->send(
            $correo,
            $nombreCompleto ?: $usuario['username'],
            'Recuperación de contraseña',
            $html
        );

        if (!$send['ok']) {
            return redirect()->back()
                ->withInput()
                ->with('toast_error', 'No se pudo enviar el correo: ' . $send['error']);
        }

        return redirect()->to('/acceso/restablecer')
            ->with('toast_success', 'Te enviamos un código de recuperación a tu correo');
    }

    public function restablecer()
    {
        $reset = session()->get('reset_password');

        if (!$reset) {
            return redirect()->to('/acceso/recuperar')
                ->with('toast_error', 'Primero solicita tu código de recuperación');
        }

        return view('acceso/restablecer', [
            'title' => 'Restablecer contraseña'
        ]);
    }

    public function actualizarPassword()
    {
        $reset = session()->get('reset_password');

        if (!$reset) {
            return redirect()->to('/acceso/recuperar')
                ->with('toast_error', 'La sesión de recuperación no existe o ya expiró');
        }

        if (time() > (int) $reset['expires_at']) {
            session()->remove('reset_password');

            return redirect()->to('/acceso/recuperar')
                ->with('toast_error', 'El código de recuperación ha expirado');
        }

        $rules = [
            'codigo'            => 'required|exact_length[6]',
            'password'          => 'required|min_length[6]',
            'password_confirm'  => 'required|matches[password]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('toast_error', $this->validator->getErrors());
        }

        $codigoIngresado = trim((string) $this->request->getPost('codigo'));
        $passwordNueva   = (string) $this->request->getPost('password');

        if ($codigoIngresado !== (string) $reset['codigo']) {
            return redirect()->back()
                ->withInput()
                ->with('toast_error', 'El código de verificación es incorrecto');
        }

        $usuario = $this->usuarioModel->find($reset['id_usuario']);

        if (!$usuario) {
            session()->remove('reset_password');
            return redirect()->to('/acceso/recuperar')
                ->with('toast_error', 'No se encontró el usuario para restablecer la contraseña');
        }

        $this->usuarioModel->update($usuario['id_usuario'], [
            'password' => password_hash($passwordNueva, PASSWORD_DEFAULT)
        ]);

        session()->remove('reset_password');

        return redirect()->to('/acceso/login')
            ->with('toast_success', 'Tu contraseña fue actualizada correctamente');
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to('/acceso/login')
            ->with('toast_success', 'Sesión cerrada correctamente');
    }
}
