<?php

namespace App\Models;
use CodeIgniter\Model;

class UserModelo extends Model{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = ['nombre','login','clave','estado','id_sucursal','id_cargo','administrador','deleted_by','updated_by','created_by'];
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
    protected $validationRules = [
        'nombre' => 'required',
        'login' => 'required',
        'clave' => 'required',
        'estado' => 'required',
        'id_sucursal' => 'required',
        'id_cargo' => 'required',
        'administrador' => 'required'
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
    public function actualizarPass($id, $nueva_pass){
        $data = [
            'clave' => password_hash($nueva_pass, PASSWORD_DEFAULT),
        ];
        $this->update($id, $data);
    }
    public function actualizarPassRes($id, $nueva_pass) {
        $data = [
            'clave' => password_hash($nueva_pass, PASSWORD_DEFAULT),
            'estado' => 1 // Actualiza el estado a 1 después de cambiar la contraseña
        ];
        return $this->update($id, $data);
    }    
    public function verificarPass($id, $pass_actual) {
        $usuario = $this->find($id);
        if ($usuario) {
            return password_verify($pass_actual, $usuario['clave']);
        }
        return false;
    }    
}
