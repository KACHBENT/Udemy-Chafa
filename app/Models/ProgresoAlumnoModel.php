<?php

namespace App\Models;

use CodeIgniter\Model;

class ProgresoAlumnoModel extends Model
{
    protected $table            = 'tbl_rel_progreso_alumno';
    protected $primaryKey       = 'id_progreso';
    protected $returnType       = 'array';
    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'fk_alumno',
        'fk_video',
        'visto',
        'fecha_visualizacion'
    ];

    public function obtenerProgresoPorAlumno($idAlumno)
    {
        return $this->select('tbl_rel_progreso_alumno.*, tbl_ope_video_leccion.titulo_video')
                    ->join('tbl_ope_video_leccion', 'tbl_ope_video_leccion.id_video = tbl_rel_progreso_alumno.fk_video', 'left')
                    ->where('tbl_rel_progreso_alumno.fk_alumno', $idAlumno)
                    ->findAll();
    }
}