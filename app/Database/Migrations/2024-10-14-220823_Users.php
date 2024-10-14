<?php

namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

class Users extends Migration{

    public function up(){
        $this->forge->addField([
            'id'            => [
                'type'           => 'INT',
                'unsigned'      => true,
                'auto_increment' => true,
            ],
            'nombre'        => [
                'type'           => 'VARCHAR',
                'constraint'     => '100',
                'collate'        => 'utf8mb4_general_ci',
                'null'           => false,
            ],
            'login'         => [
                'type'           => 'VARCHAR',
                'constraint'     => '200',
                'collate'        => 'utf8mb4_general_ci',
                'null'           => false,
            ],
            'clave'         => [
                'type'           => 'TEXT',
                'collate'        => 'utf8mb4_general_ci',
                'null'           => false,
            ],
            'estado'        => [
                'type'           => 'INT',
                'null'           => false,
            ],
            'id_local'   => [
                'type'           => 'INT',
                'unsigned'      => true,
                'null'           => true,
            ],
            'id_cargo'      => [
                'type'           => 'INT',
                'unsigned'      => true,
                'null'           => false,
            ],
            'administrador' => [
                'type'           => 'INT',
                'null'           => false,
            ],
            'created_at'    => [
                'type'           => 'DATETIME',
                'null'           => false,
            ],
            'updated_at'    => [
                'type'           => 'DATETIME',
                'null'           => false,
            ],
            'deleted_at'    => [
                'type'           => 'DATETIME',
                'null'           => true,
            ],
            'deleted_by'    => [
                'type'           => 'INT',
                'null'           => false,
            ],
            'updated_by'    => [
                'type'           => 'INT',
                'null'           => false,
            ],
            'created_by'    => [
                'type'           => 'INT',
                'null'           => false,
            ],
        ]);

        $this->forge->addPrimaryKey('id');

        $this->forge->addForeignKey('id_local', 'local', 'id', 'SET NULL', 'CASCADE'); 

        $this->forge->createTable('users');
    }

    public function down(){
        $this->forge->dropForeignKey('users', 'users_id_sucursal_foreign');
        $this->forge->dropTable('users');
    }
}
