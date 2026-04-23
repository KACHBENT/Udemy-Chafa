<?php

namespace App\Models;

use CodeIgniter\Model;

class CursoModel extends Model
{
    protected $table            = 'tbl_ope_curso';
    protected $primaryKey       = 'id_curso';
    protected $returnType       = 'array';
    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'nombre_curso',
        'descripcion',
        'fk_docente',
        'fk_categoria',
        'fk_image_fondo',
        'estatus'
    ];

    public function obtenerCursosCompletos()
    {
        return $this->select('
                        tbl_ope_curso.*,
                        tbl_cat_categoria.nombre_categoria,
                        tbl_rel_image.ruta_archivo as imagen_fondo,
                        tbl_ope_docente.especialidad,
                        tbl_rel_persona.nombre,
                        tbl_rel_persona.apellido_paterno,
                        tbl_rel_persona.apellido_materno
                    ')
                    ->join('tbl_cat_categoria', 'tbl_cat_categoria.id_categoria = tbl_ope_curso.fk_categoria', 'left')
                    ->join('tbl_rel_image', 'tbl_rel_image.id_image = tbl_ope_curso.fk_image_fondo', 'left')
                    ->join('tbl_ope_docente', 'tbl_ope_docente.id_docente = tbl_ope_curso.fk_docente', 'left')
                    ->join('tbl_rel_persona', 'tbl_rel_persona.id_persona = tbl_ope_docente.fk_persona', 'left')
                    ->findAll();
    }
}