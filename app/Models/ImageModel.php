<?php

namespace App\Models;

use CodeIgniter\Model;

class ImageModel extends Model
{
    protected $table            = 'tbl_rel_image';
    protected $primaryKey       = 'id_image';
    protected $returnType       = 'array';
    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'nombre_archivo',
        'ruta_archivo',
        'extension',
        'tamano_kb',
        'fecha_subida'
    ];
}