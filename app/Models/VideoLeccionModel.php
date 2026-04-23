<?php

namespace App\Models;

use CodeIgniter\Model;

class VideoLeccionModel extends Model
{
    protected $table            = 'tbl_ope_video_leccion';
    protected $primaryKey       = 'id_video';
    protected $returnType       = 'array';
    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'fk_curso',
        'titulo_video',
        'url_video',
        'orden_secuencia',
        'duracion_segundos'
    ];

    public function obtenerVideosPorCurso($idCurso)
    {
        return $this->where('fk_curso', $idCurso)
                    ->orderBy('orden_secuencia', 'ASC')
                    ->findAll();
    }
}