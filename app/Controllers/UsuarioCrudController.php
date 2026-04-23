<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsuarioModel;
use App\Models\PersonaModel;
use App\Models\RolModel;
use Config\Database;

class UsuarioCrudController extends BaseController
{
    protected $usuarioModel;
    protected $personaModel;
    protected $rolModel;

    public function __construct()
    {
        $this->usuarioModel = new UsuarioModel();
        $this->personaModel = new PersonaModel();
        $this->rolModel     = new RolModel();
    }

    public function index()
    {
        $usuarios = $this->usuarioModel
            ->select('
                tbl_ope_usuario.id_usuario,
                tbl_ope_usuario.username,
                tbl_ope_usuario.tema_oscuro,
                tbl_rel_persona.nombre,
                tbl_rel_persona.apellido_paterno,
                tbl_rel_persona.apellido_materno,
                tbl_cat_rol.nombre_rol
            ')
            ->join('tbl_rel_persona', 'tbl_rel_persona.id_persona = tbl_ope_usuario.fk_persona', 'left')
            ->join('tbl_cat_rol', 'tbl_cat_rol.id_rol = tbl_ope_usuario.fk_rol', 'left')
            ->orderBy('tbl_ope_usuario.id_usuario', 'DESC')
            ->findAll();

        $rows = [];

        foreach ($usuarios as $u) {
            $nombreCompleto = trim(
                ($u['nombre'] ?? '') . ' ' .
                ($u['apellido_paterno'] ?? '') . ' ' .
                ($u['apellido_materno'] ?? '')
            );

            $acciones = '
                <div class="d-flex justify-content-center gap-2 flex-wrap">
                    <a href="' . base_url('usuarios/editar/' . $u['id_usuario']) . '" 
                       class="btn btn-content-action btn-edit-tipo" 
                       title="Editar">
                        <img src="' . base_url('images/icons/edit.svg') . '" alt="Editar">
                    </a>

                    <a href="' . base_url('usuarios/eliminar/' . $u['id_usuario']) . '" 
                       class="btn btn-content-action btn-inactivar-tipo"
                       onclick="return confirm(\'¿Seguro que deseas eliminar este usuario?\')"
                       title="Eliminar">
                        <img src="' . base_url('images/icons/delete.svg') . '" alt="Eliminar">
                    </a>
                </div>
            ';

            $rows[] = [
                'id_usuario'       => $u['id_usuario'],
                'nombre_completo'  => $nombreCompleto,
                'username'         => $u['username'],
                'rol'              => $u['nombre_rol'] ?? 'Sin rol',
                'tema_oscuro'      => !empty($u['tema_oscuro']) ? 'Sí' : 'No',
                'acciones'         => $acciones,
            ];
        }

        $buttons = [
            '<a href="' . base_url('usuarios/nuevo') . '" class="btn btn-primary rounded-4 px-4">
                <span class="material-symbols-rounded align-middle me-1">person_add</span>
                Nuevo usuario
            </a>'
        ];

        return view('usuarios/index', [
            'title'               => 'Usuarios',
            'buttons'             => $buttons,
            'rows'                => $rows,
        ]);
    }

    public function nuevo()
    {
        return view('usuarios/form', [
            'title'   => 'Nuevo usuario',
            'usuario' => null,
            'persona' => null,
            'roles'   => $this->rolModel->findAll(),
            'modo'    => 'crear',
        ]);
    }

    public function guardar()
    {
        $rules = [
            'nombre'            => 'required|min_length[2]|max_length[100]',
            'apellido_paterno'  => 'required|min_length[2]|max_length[100]',
            'apellido_materno'  => 'permit_empty|max_length[100]',
            'username'          => 'required|min_length[4]|max_length[50]|is_unique[tbl_ope_usuario.username]',
            'password'          => 'required|min_length[6]',
            'fk_rol'            => 'required|integer',
            'tema_oscuro'       => 'permit_empty|in_list[0,1]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('toast_error', $this->validator->getErrors());
        }

        $db = Database::connect();
        $db->transBegin();

        try {
            $this->personaModel->insert([
                'nombre'           => trim((string)$this->request->getPost('nombre')),
                'apellido_paterno' => trim((string)$this->request->getPost('apellido_paterno')),
                'apellido_materno' => trim((string)$this->request->getPost('apellido_materno')) ?: null,
            ]);

            $idPersona = $this->personaModel->getInsertID();

            $this->usuarioModel->insert([
                'fk_persona'      => $idPersona,
                'fk_rol'          => (int)$this->request->getPost('fk_rol'),
                'fk_image_perfil' => null,
                'username'        => trim((string)$this->request->getPost('username')),
                'password'        => password_hash((string)$this->request->getPost('password'), PASSWORD_DEFAULT),
                'tema_oscuro'     => $this->request->getPost('tema_oscuro') ? 1 : 0,
            ]);

            if ($db->transStatus() === false) {
                $db->transRollback();
                return redirect()->back()->withInput()->with('toast_error', 'No fue posible guardar el usuario');
            }

            $db->transCommit();

            return redirect()->to(base_url('usuarios'))
                ->with('toast_success', 'Usuario creado correctamente');
        } catch (\Throwable $e) {
            $db->transRollback();

            return redirect()->back()
                ->withInput()
                ->with('toast_error', 'Error al guardar: ' . $e->getMessage());
        }
    }

    public function editar($id)
    {
        $usuario = $this->usuarioModel->find($id);

        if (!$usuario) {
            return redirect()->to(base_url('usuarios'))
                ->with('toast_error', 'Usuario no encontrado');
        }

        $persona = $this->personaModel->find($usuario['fk_persona']);

        return view('usuarios/form', [
            'title'   => 'Editar usuario',
            'usuario' => $usuario,
            'persona' => $persona,
            'roles'   => $this->rolModel->findAll(),
            'modo'    => 'editar',
        ]);
    }

    public function actualizar($id)
    {
        $usuario = $this->usuarioModel->find($id);

        if (!$usuario) {
            return redirect()->to(base_url('usuarios'))
                ->with('toast_error', 'Usuario no encontrado');
        }

        $rules = [
            'nombre'            => 'required|min_length[2]|max_length[100]',
            'apellido_paterno'  => 'required|min_length[2]|max_length[100]',
            'apellido_materno'  => 'permit_empty|max_length[100]',
            'username'          => 'required|min_length[4]|max_length[50]',
            'password'          => 'permit_empty|min_length[6]',
            'fk_rol'            => 'required|integer',
            'tema_oscuro'       => 'permit_empty|in_list[0,1]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('toast_error', $this->validator->getErrors());
        }

        $username = trim((string)$this->request->getPost('username'));

        $duplicado = $this->usuarioModel
            ->where('username', $username)
            ->where('id_usuario !=', $id)
            ->first();

        if ($duplicado) {
            return redirect()->back()
                ->withInput()
                ->with('toast_error', 'El nombre de usuario ya existe');
        }

        $this->personaModel->update($usuario['fk_persona'], [
            'nombre'           => trim((string)$this->request->getPost('nombre')),
            'apellido_paterno' => trim((string)$this->request->getPost('apellido_paterno')),
            'apellido_materno' => trim((string)$this->request->getPost('apellido_materno')) ?: null,
        ]);

        $dataUsuario = [
            'username'    => $username,
            'fk_rol'      => (int)$this->request->getPost('fk_rol'),
            'tema_oscuro' => $this->request->getPost('tema_oscuro') ? 1 : 0,
        ];

        $password = (string)$this->request->getPost('password');
        if ($password !== '') {
            $dataUsuario['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $this->usuarioModel->update($id, $dataUsuario);

        return redirect()->to(base_url('usuarios'))
            ->with('toast_success', 'Usuario actualizado correctamente');
    }

    public function eliminar($id)
    {
        $usuario = $this->usuarioModel->find($id);

        if (!$usuario) {
            return redirect()->to(base_url('usuarios'))
                ->with('toast_error', 'Usuario no encontrado');
        }

        $this->usuarioModel->delete($id);

        return redirect()->to(base_url('usuarios'))
            ->with('toast_success', 'Usuario eliminado correctamente');
    }
}