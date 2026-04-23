<?php

namespace App\Models;

use CodeIgniter\Model;

class TipoContactoModel extends Model
{
    protected $table            = 'tbl_cat_tipocontacto';
    protected $primaryKey       = 'id_tipocontacto';
    protected $returnType       = 'array';
    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'descripcion'
    ];
}