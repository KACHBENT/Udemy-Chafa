<?php namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RolesSeeder extends Seeder
{
    public function run()
    {
        $this->db->table('tbl_cat_roles')->ignore()->insertBatch([
            ['roles_Valor' => 'Administrador', 'roles_Activo' => 1],
            ['roles_Valor' => 'Empleado',      'roles_Activo' => 1],
        ]);
    }
}