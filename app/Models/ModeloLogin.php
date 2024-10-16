<?php

namespace App\Models;

use CodeIgniter\Model;

class ModeloLogin extends Model{
    protected $table            = 'modelologins';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
    public function paginas($data){
        $db = \Config\Database::connect();
        $qry = 'CALL sp_listar_accesos(?)';
        $result = $db->query($qry, $data);
        $db->close();
        $data = $result->getResultArray();
        $lista = array();
        foreach ($data as $obj) {
            $controller = $obj['v2'];
            $isApi = $obj['v3'];
            if ($isApi == 1) {
                $lista[] = strtoupper('\APP\CONTROLLERS\API' . '\\' . $controller);
            } else {
                $lista[] = strtoupper('\APP\CONTROLLERS' . '\\' . $controller);
            }
        }
        return $lista;
    }
    public function login($user, $pass){
        $db = \Config\Database::connect();
        $qry = 'CALL sp_validarUsuario(?,?)'; 
        $result = $db->query($qry, [$user, $pass]);
        $db->close();
        return $result->getRow();
    }
    public function getUserById($userId)
    {
        return $this->where('id', $userId)->first();
    }
}
