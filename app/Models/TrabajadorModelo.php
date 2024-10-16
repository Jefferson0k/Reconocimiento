<?php

namespace App\Models;

use CodeIgniter\Model;

class TrabajadorModelo extends Model{
    protected $table            = 'trabajadores';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = ['dni','nombres','Apellidos','telefono','foto','id_sucursal','id_horario','id_user','estado','deleted_by','updated_by'];

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
    protected $validationRules = [
        'dni' => 'required', 
        'nombres' => 'required',
        'Apellidos' => 'required',
        'telefono' => 'required',
        'id_sucursal' => 'required',
        'id_horario' => 'required',
        'estado'=> 'required',
        'id_user'=> 'required',
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
    public function insertDataFromCsv($file_path, $id_sucursal,$id_user) {
        $rutaSucursal = ROOTPATH . 'public/Trabajadores/Sucursales/' . $id_sucursal . '/';
        if (!is_dir($rutaSucursal)) {
            mkdir($rutaSucursal, 0755, true);
        }
    
        if (($handle = fopen($file_path, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
                $insert_data = [
                    'dni' => $data[0],
                    'nombres' => $data[1],
                    'Apellidos' => $data[2],
                    'telefono' => $data[3],
                    'id_sucursal' => $id_sucursal,
                    'id_horario' => $data[4],
                    'id_user' => $id_user,
                    'estado' => $data[5],
                ];
                $nombreImagenPorDefecto = uniqid() . '.jpg';
                $rutaImagenPorDefecto = ROOTPATH . 'public/Trabajadores/SinFoto/default.jpg';
                $nuevaRutaImagen = $rutaSucursal . $nombreImagenPorDefecto;
                copy($rutaImagenPorDefecto, $nuevaRutaImagen);
                $insert_data['foto'] = $nombreImagenPorDefecto;
                $this->insert($insert_data);
            }
            fclose($handle);
        } else {
            throw new \Exception("Error al abrir el archivo CSV.");
        }
    }     
    public function updateData($id, $data) {
        return $this->update($id, $data);
    }                  
}
