<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Local extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'         => [
                'type'           => 'INT',
                'unsigned'      => true,
                'auto_increment' => true,
            ],
            'nombre'     => [
                'type'           => 'VARCHAR',
                'constraint'     => '100',
                'collate'        => 'utf8mb4_general_ci',
                'null'           => false,
            ],
            'direccion'  => [
                'type'           => 'TEXT',
                'collate'        => 'utf8mb4_general_ci',
                'null'           => false,
            ],
            'id_user'    => [
                'type'           => 'INT',
                'unsigned'      => true,
                'null'           => false,
            ],
            'estado'     => [
                'type'           => 'INT',
                'null'           => false,
            ],
            'created_at' => [
                'type'           => 'DATETIME',
                'null'           => false,
            ],
            'updated_at' => [
                'type'           => 'DATETIME',
                'null'           => false,
            ],
            'deleted_at' => [
                'type'           => 'DATETIME',
                'null'           => true,
            ],
            'deleted_by' => [
                'type'           => 'INT',
                'null'           => false,
            ],
            'updated_by' => [
                'type'           => 'INT',
                'null'           => false,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('local');
    }

    public function down()
    {
        $this->forge->dropTable('local');
    }
}
