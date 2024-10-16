<?php

namespace App\Models;

use CodeIgniter\Model;

class CargoModelo extends Model
{
    protected $table = 'cargo';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $protectFields = true;
    protected $allowedFields = ['Nombre', 'Estado', 'deleted_by', 'updated_by', 'created_by'];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    protected $validationRules = [
        'Nombre' => 'required',
        'Estado' => 'required'
    ];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    public function getNombreById($id)
    {
        return $this->where('id', $id)->first()['Nombre'];
    }
}
