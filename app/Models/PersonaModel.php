<?php

namespace App\Models;

use CodeIgniter\Model;

class PersonaModel extends Model
{
    protected $table            = 'tbl_rel_persona';
    protected $primaryKey       = 'id_persona';
    protected $returnType       = 'array';
    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'fecha_registro'
    ];
}