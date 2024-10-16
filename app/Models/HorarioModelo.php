<?php

namespace App\Models;

use CodeIgniter\Model;
use DateTime;
use DateTimeZone;

class HorarioModelo extends Model {
    protected $table            = 'horarios';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'ingreso', 'salida', 'break_entrada', 'break_salida', 'descripcion', 
        'estado', 'totalHoras', 'id_sucursal', 'id_Turnos', 'id_user', 
        'deleted_by', 'updated_by'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;
    public $DBDebug = true;
    protected array $casts = [];
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
    protected array $castHandlers = [];

    // Validation
    protected $validationRules      = [
        'ingreso' => 'required',
        'salida' => 'required',
        'break_entrada' => 'required',
        'break_salida' => 'required',
        'descripcion' => 'required',
        'estado' => 'required',
        'totalHoras' => 'required',
        'id_sucursal' => 'required',
        'id_Turnos' => 'required',
        'id_user' => 'required',
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['addTimestamps'];
    protected $afterInsert    = [];
    protected $beforeUpdate   = ['updateTimestamps'];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = ['addDeletedTimestamp'];
    protected $afterDelete    = [];
    
    protected function addTimestamps(array $data){
        $peruTimezone = new DateTimeZone('America/Lima');
        $now = new DateTime('now', $peruTimezone);
        $data['data']['created_at'] = $now->format('Y-m-d H:i:s');
        return $data;
    }

    protected function updateTimestamps(array $data){
        // Establecer la zona horaria de Perú
        $peruTimezone = new DateTimeZone('America/Lima');
        $now = new DateTime('now', $peruTimezone);
        
        // Formatear la fecha en el formato deseado
        $data['data']['updated_at'] = $now->format('Y-m-d H:i:s');
        return $data;
    }
    protected function addDeletedTimestamp(array $data) {
        $peruTimezone = new DateTimeZone('America/Lima');
        $now = new DateTime('now', $peruTimezone);
        $data['data'][$this->deletedField] = $now->format('Y-m-d H:i:s');
        $session = session();
        $id_user = $session->get('id');
        if ($id_user) {
            $data['data']['deleted_by'] = $id_user;
        }

        return $data;
    }
    public function insertDataFromCsv($file_path, $id_sucursal, $id_user) {
        // Verificar si el archivo se puede abrir
        if (($handle = fopen($file_path, "r")) === FALSE) {
            return false;
        }
        
        $successCount = 0;
        $this->db->transStart(); // Iniciar una transacción
    
        while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
            // Verificar si el número de campos es correcto
            if (count($data) == 8) {
                $insertData = [
                    'ingreso' => $data[0],
                    'salida' => $data[1],
                    'break_entrada' => $data[2],
                    'break_salida' => $data[3],
                    'descripcion' => $data[4],
                    'estado' => $data[5],
                    'totalHoras' => $data[6],
                    'id_sucursal' => $id_sucursal,
                    'id_user' => $id_user,
                    'id_Turnos' => $data[7],
                ];
                // Intentar insertar los datos y contar los éxitos
                if ($this->insert($insertData)) {
                    $successCount++;
                }
            } else {
                // Manejo de error por número incorrecto de campos
                $this->db->transRollback(); // Revertir la transacción si hay un error
                fclose($handle);
                return false;
            }
        }
    
        fclose($handle);
        $this->db->transComplete(); // Completar la transacción
    
        if ($this->db->transStatus() === FALSE) {
            // Transacción fallida
            return false;
        }
    
        return $successCount; // Retornar el número de inserciones exitosas
    }        
}