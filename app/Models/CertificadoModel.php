<?php

namespace App\Models;

use CodeIgniter\Model;

class CertificadoModel extends Model
{
    protected $table            = 'tbl_ope_certificado';
    protected $primaryKey       = 'id_certificado';
    protected $returnType       = 'array';
    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'fk_alumno',
        'fk_curso',
        'codigo_verificacion',
        'url_archivo',
        'fecha_emision'
    ];

    public function obtenerCertificados()
    {
        return $this->select('
                        tbl_ope_certificado.*,
                        tbl_ope_curso.nombre_curso,
                        tbl_rel_persona.nombre,
                        tbl_rel_persona.apellido_paterno,
                        tbl_rel_persona.apellido_materno
                    ')
                    ->join('tbl_ope_curso', 'tbl_ope_curso.id_curso = tbl_ope_certificado.fk_curso', 'left')
                    ->join('tbl_ope_alumno', 'tbl_ope_alumno.id_alumno = tbl_ope_certificado.fk_alumno', 'left')
                    ->join('tbl_rel_persona', 'tbl_rel_persona.id_persona = tbl_ope_alumno.fk_persona', 'left')
                    ->findAll();
    }
}