<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PersonaModel;
use App\Models\UsuarioModel;
use App\Models\ImageModel;
use Config\Database;

class PerfilController extends BaseController
{
    protected $personaModel;
    protected $usuarioModel;
    protected $imageModel;

    public function __construct()
    {
        $this->personaModel = new PersonaModel();
        $this->usuarioModel = new UsuarioModel();
        $this->imageModel   = new ImageModel();
    }

    public function index()
    {
        if (!session('isLoggedIn')) {
            return redirect()->to('/acceso/login')
                ->with('toast_error', 'Debes iniciar sesión para acceder a tu perfil');
        }

        $idUsuario = session('id_usuario');
        $fkPersona = session('fk_persona');

        $usuario = $this->usuarioModel
            ->select('tbl_ope_usuario.*, tbl_rel_image.ruta_archivo as imagen_perfil')
            ->join('tbl_rel_image', 'tbl_rel_image.id_image = tbl_ope_usuario.fk_image_perfil', 'left')
            ->find($idUsuario);

        $persona = $this->personaModel->find($fkPersona);

        if (!$usuario || !$persona) {
            return redirect()->to('/')
                ->with('toast_error', 'No se encontró la información del perfil');
        }

        return view('perfil/index', [
            'title'   => 'Mi perfil',
            'usuario' => $usuario,
            'persona' => $persona,
        ]);
    }

    public function actualizar()
    {
        if (!session('isLoggedIn')) {
            return redirect()->to('/acceso/login')
                ->with('toast_error', 'Debes iniciar sesión para continuar');
        }

        $rules = [
            'nombre'            => 'required|min_length[2]|max_length[100]',
            'apellido_paterno'  => 'required|min_length[2]|max_length[100]',
            'apellido_materno'  => 'permit_empty|max_length[100]',
            'username'          => 'required|min_length[4]|max_length[50]',
            'foto_perfil'       => 'permit_empty|is_image[foto_perfil]|mime_in[foto_perfil,image/jpg,image/jpeg,image/png,image/webp]|max_size[foto_perfil,4096]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('toast_error', $this->validator->getErrors());
        }

        $idUsuario = (int) session('id_usuario');
        $fkPersona = (int) session('fk_persona');

        $usuarioActual = $this->usuarioModel->find($idUsuario);
        $personaActual = $this->personaModel->find($fkPersona);

        if (!$usuarioActual || !$personaActual) {
            return redirect()->back()
                ->with('toast_error', 'No se encontró el perfil actual');
        }

        $username = trim((string) $this->request->getPost('username'));

        $usuarioDuplicado = $this->usuarioModel
            ->where('username', $username)
            ->where('id_usuario !=', $idUsuario)
            ->first();

        if ($usuarioDuplicado) {
            return redirect()->back()
                ->withInput()
                ->with('toast_error', 'Ese nombre de usuario ya está siendo utilizado');
        }

        $db = Database::connect();
        $db->transBegin();

        try {
            $this->personaModel->update($fkPersona, [
                'nombre'            => trim((string) $this->request->getPost('nombre')),
                'apellido_paterno'  => trim((string) $this->request->getPost('apellido_paterno')),
                'apellido_materno'  => trim((string) $this->request->getPost('apellido_materno')) ?: null,
            ]);

            $dataUsuario = [
                'username' => $username,
            ];

            $archivo = $this->request->getFile('foto_perfil');

            if ($archivo && $archivo->isValid() && !$archivo->hasMoved()) {
                $nombreOriginal = $archivo->getClientName();
                $extension      = $archivo->getExtension();
                $tamanoKB       = (int) ceil($archivo->getSize() / 1024);

                $nuevoNombre = $archivo->getRandomName();
                $archivo->move(FCPATH . 'uploads/users', $nuevoNombre);

                $rutaBD = '/uploads/users/' . $nuevoNombre;

                $this->imageModel->insert([
                    'nombre_archivo' => $nombreOriginal,
                    'ruta_archivo'   => $rutaBD,
                    'extension'      => $extension,
                    'tamano_kb'      => $tamanoKB,
                ]);

                $idImagen = $this->imageModel->getInsertID();

                $dataUsuario['fk_image_perfil'] = $idImagen;
            }

            $this->usuarioModel->update($idUsuario, $dataUsuario);

            if ($db->transStatus() === false) {
                $db->transRollback();

                return redirect()->back()
                    ->withInput()
                    ->with('toast_error', 'No fue posible actualizar el perfil');
            }

            $db->transCommit();

            // Recargar usuario actualizado para sesión
            $usuarioActualizado = $this->usuarioModel
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
                ->find($idUsuario);

            $nombreCompleto = trim(
                ($usuarioActualizado['nombre'] ?? '') . ' ' .
                ($usuarioActualizado['apellido_paterno'] ?? '') . ' ' .
                ($usuarioActualizado['apellido_materno'] ?? '')
            );

            session()->set([
                'username'        => $usuarioActualizado['username'],
                'nombre_completo' => $nombreCompleto,
                'nombre_rol'      => $usuarioActualizado['nombre_rol'] ?? session('nombre_rol'),
                'imagen_perfil'   => $usuarioActualizado['imagen_perfil'] ?? null,
            ]);

            return redirect()->to('/perfil')
                ->with('toast_success', 'Tu perfil fue actualizado correctamente');
        } catch (\Throwable $e) {
            $db->transRollback();

            return redirect()->back()
                ->withInput()
                ->with('toast_error', 'Ocurrió un error al actualizar el perfil: ' . $e->getMessage());
        }
    }
}