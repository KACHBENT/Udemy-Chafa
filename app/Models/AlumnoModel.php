<?php

namespace App\Models;

use CodeIgniter\Model;

class AlumnoModel extends Model
{
    protected $table            = 'tbl_ope_alumno';
    protected $primaryKey       = 'id_alumno';
    protected $returnType       = 'array';
    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'fk_persona',
        'matricula'
    ];

    public function obtenerAlumnos()
    {
        return $this->select('tbl_ope_alumno.*, tbl_rel_persona.nombre, tbl_rel_persona.apellido_paterno, tbl_rel_persona.apellido_materno')
                    ->join('tbl_rel_persona', 'tbl_rel_persona.id_persona = tbl_ope_alumno.fk_persona', 'left')
                    ->findAll();
    }
}