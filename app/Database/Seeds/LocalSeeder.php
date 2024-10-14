<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class LocalSeeder extends Seeder{

    public function run(){
        $data = [
            [
                'nombre'      => 'Sucursal 1',
                'direccion'   => 'Direccion de la Sucursal 1',
                'id_user'     => 1,
                'estado'      => 1,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
                'deleted_at'  => null,
                'deleted_by'  => null,
                'updated_by'  => 1,
            ],
            [
                'nombre'      => 'Sucursal 2',
                'direccion'   => 'Direccion de la Sucursal 2',
                'id_user'     => 1, 
                'estado'      => 1,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
                'deleted_at'  => null,
                'deleted_by'  => null,
                'updated_by'  => 1, 
            ],
        ];
        $this->db->table('local')->insertBatch($data);
    }
}
