<?php

namespace App\Models;

use CodeIgniter\Model;

class DocenteModel extends Model
{
    protected $table            = 'tbl_ope_docente';
    protected $primaryKey       = 'id_docente';
    protected $returnType       = 'array';
    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'fk_persona',
        'especialidad'
    ];

    public function obtenerDocentes()
    {
        return $this->select('tbl_ope_docente.*, tbl_rel_persona.nombre, tbl_rel_persona.apellido_paterno, tbl_rel_persona.apellido_materno')
                    ->join('tbl_rel_persona', 'tbl_rel_persona.id_persona = tbl_ope_docente.fk_persona', 'left')
                    ->findAll();
    }
}