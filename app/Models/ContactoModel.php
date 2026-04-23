<?php

namespace App\Models;

use CodeIgniter\Model;

class ContactoModel extends Model
{
    protected $table            = 'tbl_ope_contacto';
    protected $primaryKey       = 'id_contacto';
    protected $returnType       = 'array';
    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'fk_persona',
        'fk_tipocontacto',
        'valor_contacto',
        'es_verificado'
    ];

    public function obtenerContactosConTipo()
    {
        return $this->select('tbl_ope_contacto.*, tbl_cat_tipocontacto.descripcion as tipo_contacto')
                    ->join('tbl_cat_tipocontacto', 'tbl_cat_tipocontacto.id_tipocontacto = tbl_ope_contacto.fk_tipocontacto', 'left')
                    ->findAll();
    }
}