<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoriaModel extends Model
{
    protected $table            = 'tbl_cat_categoria';
    protected $primaryKey       = 'id_categoria';
    protected $returnType       = 'array';
    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'nombre_categoria',
        'descripcion'
    ];
}