<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Asistencia extends Migration{

    public function up(){
        $this->forge->addField([
            'id'                => [
                'type'           => 'INT',
                'unsigned'      => true,
                'auto_increment' => true,
            ],
            'fecha'             => [
                'type'           => 'DATE',
                'null'           => false,
            ],
            'hora_entrada'      => [
                'type'           => 'TIME',
                'null'           => false,
            ],
            'break_inicio'      => [
                'type'           => 'TIME',
                'null'           => true,
            ],
            'break_final'       => [
                'type'           => 'TIME',
                'null'           => true,
            ],
            'hora_salida'       => [
                'type'           => 'TIME',
                'null'           => true,
            ],
            'horas_trabajadas'  => [
                'type'           => 'TIME',
                'null'           => true,
            ],
            'horas_extras'      => [
                'type'           => 'TIME',
                'null'           => true,
            ],
            'horas_tardanzas'   => [
                'type'           => 'TIME',
                'null'           => true,
            ],
            'tardanza_break'     => [
                'type'           => 'TIME',
                'null'           => true,
            ],
            'id_trabajador'     => [
                'type'           => 'INT',
                'unsigned'      => true,
                'null'           => true,
            ],
            'id_local'       => [
                'type'           => 'INT',
                'unsigned'      => true,
                'null'           => false,
            ],
            'id_observaciones'  => [
                'type'           => 'INT',
                'unsigned'      => true,
                'null'           => false,
            ],
            'estado'            => [
                'type'           => 'INT',
                'null'           => false,
            ],
            'created_at'        => [
                'type'           => 'DATETIME',
                'null'           => false,
            ],
            'updated_at'        => [
                'type'           => 'DATETIME',
                'null'           => false,
            ],
            'deleted_at'        => [
                'type'           => 'DATETIME',
                'null'           => true,
            ],
        ]);
    
        $this->forge->addPrimaryKey('id');

        $this->forge->addForeignKey('id_trabajador', 'trabajador', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('id_local', 'local', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('asistencia');
    }

    public function down(){
        $this->forge->dropForeignKey('asistencia', 'asistencia_id_trabajador_foreign');
        $this->forge->dropForeignKey('asistencia', 'asistencia_id_local_foreign');
        $this->forge->dropTable('asistencia');
    }
}
