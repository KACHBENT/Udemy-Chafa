<?php

namespace App\Models;

use CodeIgniter\Model;

class ValoracionModel extends Model
{
    protected $table            = 'tbl_ope_valoracion';
    protected $primaryKey       = 'id_valoracion';
    protected $returnType       = 'array';
    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'fk_curso',
        'fk_alumno',
        'estrellas',
        'comentario',
        'fecha_valoracion'
    ];

    public function obtenerValoracionesPorCurso($idCurso)
    {
        return $this->select('
                        tbl_ope_valoracion.*,
                        tbl_rel_persona.nombre,
                        tbl_rel_persona.apellido_paterno,
                        tbl_rel_persona.apellido_materno
                    ')
                    ->join('tbl_ope_alumno', 'tbl_ope_alumno.id_alumno = tbl_ope_valoracion.fk_alumno', 'left')
                    ->join('tbl_rel_persona', 'tbl_rel_persona.id_persona = tbl_ope_alumno.fk_persona', 'left')
                    ->where('tbl_ope_valoracion.fk_curso', $idCurso)
                    ->findAll();
    }
}