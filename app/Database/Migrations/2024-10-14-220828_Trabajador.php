<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Trabajador extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => [
                'type'           => 'INT',
                'unsigned'      => true,
                'auto_increment' => true,
            ],
            'dni'         => [
                'type'           => 'VARCHAR',
                'constraint'     => '8',
                'collate'        => 'utf8mb4_general_ci',
                'null'           => false,
            ],
            'nombres'     => [
                'type'           => 'VARCHAR',
                'constraint'     => '200',
                'collate'        => 'utf8mb4_general_ci',
                'null'           => false,
            ],
            'apellidos'   => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'collate'        => 'utf8mb4_general_ci',
                'null'           => false,
            ],
            'telefono'    => [
                'type'           => 'INT',
                'null'           => false,
            ],
            'foto'        => [
                'type'           => 'TEXT',
                'collate'        => 'utf8mb4_general_ci',
                'null'           => false,
            ],
            'id_local'    => [
                'type'           => 'INT',
                'unsigned'      => true,
                'null'           => true,
            ],
            'id_horario'  => [
                'type'           => 'INT',
                'unsigned'      => true,
                'null'           => true,
            ],
            'id_user'     => [
                'type'           => 'INT',
                'unsigned'      => true,
                'null'           => false,
            ],
            'estado'      => [
                'type'           => 'INT',
                'null'           => true,
            ],
            'created_at'  => [
                'type'           => 'DATETIME',
                'null'           => false,
            ],
            'updated_at'  => [
                'type'           => 'DATETIME',
                'null'           => false,
            ],
            'deleted_at'  => [
                'type'           => 'DATETIME',
                'null'           => true,
            ],
            'deleted_by'  => [
                'type'           => 'INT',
                'null'           => false,
            ],
            'updated_by'  => [
                'type'           => 'INT',
                'null'           => false,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('id_local', 'local', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('id_horario', 'horarios', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('id_user', 'users', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('trabajador');
    }

    public function down(){
        $this->forge->dropForeignKey('trabajador', 'trabajador_id_local_foreign');
        $this->forge->dropForeignKey('trabajador', 'trabajador_id_horario_foreign');
        $this->forge->dropForeignKey('trabajador', 'trabajador_id_user_foreign');
        $this->forge->dropTable('trabajador');
    }
}
