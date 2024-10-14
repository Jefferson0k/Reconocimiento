<?php

namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

class Horarios extends Migration{

    public function up(){
        $this->forge->addField([
            'id'             => [
                'type'           => 'INT',
                'unsigned'      => true,
                'auto_increment' => true,
            ],
            'ingreso'        => [
                'type'           => 'TIME',
                'null'           => false,
            ],
            'salida'         => [
                'type'           => 'TIME',
                'null'           => false,
            ],
            'break_entrada'  => [
                'type'           => 'TIME',
                'null'           => false,
            ],
            'break_salida'   => [
                'type'           => 'TIME',
                'null'           => false,
            ],
            'descripcion'    => [
                'type'           => 'TEXT',
                'collate'        => 'utf8mb4_general_ci',
                'null'           => false,
            ],
            'estado'         => [
                'type'           => 'INT',
                'null'           => false,
            ],
            'totalHoras'     => [
                'type'           => 'TIME',
                'null'           => false,
            ],
            'id_local'    => [
                'type'           => 'INT',
                'unsigned'      => true,
                'null'           => false,
            ],
            'id_turnos'      => [
                'type'           => 'INT',
                'unsigned'      => true,
                'null'           => false,
            ],
            'id_user'        => [
                'type'           => 'INT',
                'unsigned'      => true,
                'null'           => false,
            ],
            'created_at'     => [
                'type'           => 'DATETIME',
                'null'           => false,
            ],
            'updated_at'     => [
                'type'           => 'DATETIME',
                'null'           => false,
            ],
            'deleted_at'     => [
                'type'           => 'DATETIME',
                'null'           => true,
            ],
            'deleted_by'     => [
                'type'           => 'INT',
                'null'           => false,
            ],
            'updated_by'     => [
                'type'           => 'INT',
                'null'           => false,
            ],
        ]);

        $this->forge->addPrimaryKey('id');

        $this->forge->addForeignKey('id_local', 'local', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_user', 'users', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('horarios');
    }

    public function down(){
        $this->forge->dropForeignKey('horarios', 'horarios_id_local_foreign');
        $this->forge->dropForeignKey('horarios', 'horarios_id_user_foreign');

        $this->forge->dropTable('horarios');
    }
}