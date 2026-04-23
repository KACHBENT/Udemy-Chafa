<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table            = 'tbl_ope_usuario';
    protected $primaryKey       = 'id_usuario';
    protected $returnType       = 'array';
    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'fk_persona',
        'fk_rol',
        'fk_image_perfil',
        'username',
        'password',
        'tema_oscuro'
    ];

    public function obtenerUsuariosCompletos()
    {
        return $this->select('
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
                    ->findAll();
    }

    public function obtenerPorUsername($username)
    {
        return $this->where('username', $username)->first();
    }
}