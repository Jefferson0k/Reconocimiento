<?php

namespace App\Models;

use CodeIgniter\Model;
use DateTime;
use DateTimeZone;
class TurnosModelo extends Model {
    protected $table            = 'turnos';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = ['Turno', 'estado', 'id_sucursal', 'id_user', 'deleted_by', 'updated_by'];
    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;
    public $DBDebug = true;
    protected array $casts = [];
    protected array $castHandlers = [];
    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
    // Validation
    protected $validationRules      = [
        'Turno'       => 'required',
        'estado'      => 'required',
        'id_sucursal' => 'required',
        'id_user'     => 'required',
    ];
    protected $validationMessages   = [
        'Turno' => [
            'required' => 'El campo Turno es obligatorio.'
        ],
        'estado' => [
            'required' => 'El campo estado es obligatorio.'
        ],
        'id_sucursal' => [
            'required' => 'El campo id_sucursal es obligatorio.'
        ],
        'id_user' => [
            'required' => 'El campo id_user es obligatorio.'
        ],
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
    public function insertDataFromCsv($file_path, $id_sucursal, $id_user) {
        if (!file_exists($file_path) || !is_readable($file_path)) {
            return false; // File doesn't exist or is not readable
        }

        if (($handle = fopen($file_path, "r")) === FALSE) {
            return false; // Failed to open file
        }

        $successCount = 0;
        $this->db->transStart();

        while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
            if (count($data) == 2) {
                $insertData = [
                    'Turno' => $data[0],
                    'estado' => $data[1],
                    'id_sucursal' => $id_sucursal,
                    'id_user' => $id_user
                ];
                if ($this->insert($insertData)) {
                    $successCount++;
                } else {
                    $this->db->transRollback();
                    fclose($handle);
                    return false;
                }
            } else {
                $this->db->transRollback();
                fclose($handle);
                return false;
            }
        }

        fclose($handle);
        $this->db->transComplete();

        if ($this->db->transStatus() === FALSE) {
            return false;
        }

        return $successCount;
    }
}