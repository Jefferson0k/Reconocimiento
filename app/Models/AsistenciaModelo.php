<?php

namespace App\Models;
use CodeIgniter\Model;
class AsistenciaModelo extends Model{
    protected $table            = 'asistencia';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = ['fecha','hora_entrada','break_inicio','break_final','hora_salida','horas_trabajadas','horas_extras','horas_tardanzas','tardanza_break','id_trabajador','id_sucursal','id_Observaciones','estado'];
    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;
    public $DBDebug = true;
    protected array $casts = [];
    protected array $castHandlers = [
        'id_trabajador' => 'required',
        'id_sucursal' => 'required'
    ];
    // Dates
    protected $useTimestamps = true;
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
    protected $beforeInsert   = ['addTimestamps'];
    protected $afterInsert    = [];
    protected $beforeUpdate   = ['updateTimestamps'];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
    protected function addTimestamps(array $data){
        $data['data']['created_at'] = date('Y-m-d H:i:s');
        $data['data']['updated_at'] = date('Y-m-d H:i:s');
        return $data;
    }
    protected function updateTimestamps(array $data){
        $data['data']['updated_at'] = date('Y-m-d H:i:s');
        return $data;
    }
}
