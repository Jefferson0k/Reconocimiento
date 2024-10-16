<?php

namespace App\Models;

use CodeIgniter\Model;
use DateTime;
use DateTimeZone;

class SucursalModelo extends Model {
    protected $table            = 'sucursales';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = ['nombre', 'direccion', 'id_user', 'estado', 'deleted_by', 'updated_by'];
    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;
    public $DBDebug = true;
    protected array $casts = [];
    protected array $castHandlers = [];
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
    // Validation
    protected $validationRules = [
        'nombre' => 'required',
        'direccion' => 'required',
        'estado' => 'required',
        'id_user' => 'required',
    ];
    protected $validationMessages   = [];
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
    protected function addTimestamps(array $data) {
        $peruTimezone = new DateTimeZone('America/Lima');
        $now = new DateTime('now', $peruTimezone);
        $data['data'][$this->createdField] = $now->format('Y-m-d H:i:s');
        return $data;
    }
    protected function updateTimestamps(array $data) {
        $peruTimezone = new DateTimeZone('America/Lima');
        $now = new DateTime('now', $peruTimezone);
        $data['data'][$this->updatedField] = $now->format('Y-m-d H:i:s');
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
    public function insertDataFromCsv($file_path, $id_user) {
        $rutaPrincipal = ROOTPATH . 'public/Trabajadores/Sucursales/';
        if (!is_dir($rutaPrincipal)) {
            mkdir($rutaPrincipal, 0755, true);
        }
        if (($handle = fopen($file_path, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
                if (count($data) >= 3) { // Ajustar el conteo de columnas esperadas
                    $insert_data = [
                        'nombre' => $data[0],
                        'direccion' => $data[1],
                        'estado' => $data[2],
                        'id_user' => $id_user, // Añadir el id_user al array de datos
                    ];
                    if ($this->validate($insert_data)) {
                        $idSucursal = $this->insert($insert_data);
                        $rutaCarpetaSucursal = $rutaPrincipal . $idSucursal . '/';
                        if (!is_dir($rutaCarpetaSucursal)) {
                            mkdir($rutaCarpetaSucursal, 0755, true);
                        }
                    } else {
                        // Maneja la validación fallida aquí
                        echo "Datos inválidos para la fila: " . implode(";", $data) . "\n";
                    }
                } else {
                    echo "Datos incompletos para la fila: " . implode(";", $data) . "\n";
                }
            }
            fclose($handle);
        } else {
            echo "Error al abrir el archivo CSV.";
        }
    }
}