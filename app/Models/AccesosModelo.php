<?php

namespace App\Models;

use CodeIgniter\Model;

class AccesosModelo extends Model
{
    protected $table = 'accesos';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $protectFields = true;
    protected $allowedFields = ['estado', 'id_cargo', 'id_pagina', 'deleted_by', 'updated_by', 'created_by'];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    protected $validationRules = [
        'estado' => 'required',
        'id_cargo' => 'required',
        'id_pagina' => 'required'
    ];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;
}
