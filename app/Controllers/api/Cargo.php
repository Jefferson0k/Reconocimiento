<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\AccesosModelo;
use App\Models\CargoModelo;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;
class Cargo extends BaseController{
    use ResponseTrait; 
    protected $model;
    public function __construct(){
        $this->model = new CargoModelo();
    }
    public function vista(){
        return view('Dashboard/Plantillas/index').view('Dashboard/Plantillas/aside').view('Dashboard/Modulos/Cargos/Cargos').view('Dashboard/Plantillas/footer');
    }
    public function index(){
        $session = session();
        $userId = $session->get('id');
            if (!$userId) {
            return $this->failUnauthorized('Usuario no autenticado.');
        }
        $Cargo = $this->model->where('Estado !=', 0)->findAll();
        $response = ['data' => $Cargo];
        return $this->respond($response, ResponseInterface::HTTP_OK);
    }
    public function indexVista() {
        $session = session();
        $userId = $session->get('id');
            if (!$userId) {
            return $this->failUnauthorized('Usuario no autenticado.');
        }
        $Cargo = $this->model->whereIn('Estado', [0, 1])->findAll();
        $response = ['data' => $Cargo];
        return $this->respond($response, ResponseInterface::HTTP_OK);
    }
    public function store()
{
    $data = $this->request->getJSON();

    // Verifica si $data es un objeto stdClass y conviértelo a array si es necesario
    if (is_object($data)) {
        $data = (array) $data;
    }

    // Verificar que se recibieron los datos correctamente
    if (isset($data['Nombre'], $data['Estado'], $data['idPaginas'])) {
        // Obtener ID del usuario autenticado
        $session = \Config\Services::session();
        $updated_by = $session->get('id');

        // Insertar el nuevo cargo
        $cargoModel = new \App\Models\CargoModelo(); // Ajustar según el nombre de tu modelo

        $cargoData = [
            'Nombre' => $data['Nombre'],
            'Estado' => $data['Estado'],
            'created_by' => $updated_by
        ];

        // Insertar el cargo en la base de datos
        if ($cargoModel->insert($cargoData)) {
            $id_cargo = $cargoModel->getInsertID();

            // Insertar accesos asociados al cargo
            $accesosModelo = new \App\Models\AccesosModelo(); // Ajustar según el nombre de tu modelo

            foreach ($data['idPaginas'] as $id_pagina) {
                $accesoData = [
                    'estado' => 1, // Estado predeterminado
                    'id_cargo' => $id_cargo,
                    'id_pagina' => $id_pagina
                ];

                // Insertar acceso en la tabla de accesos
                if (!$accesosModelo->insert($accesoData)) {
                    return $this->fail($accesosModelo->errors());
                }
            }

            return $this->respondCreated(['success' => true, 'id_cargo' => $id_cargo]);
        } else {
            return $this->fail($cargoModel->errors());
        }
    } else {
        return $this->failValidationError('No se recibieron datos válidos para insertar.');
    }
}

    public function show($Id){
        $session = session();
        $userId = $session->get('id');
            if (!$userId) {
            return $this->failUnauthorized('Usuario no autenticado.');
        }
        $Cargo = $this->model->find($Id);
        if (!$Cargo) {
            return $this->failNotFound('La Sucursal no existe');
        }
        return $this->respond($Cargo, ResponseInterface::HTTP_OK);
    }
    public function update($Id): ResponseInterface {
        $input = $this->request->getJSON(true); // Decodificar JSON
        $Nombre = $input['Nombre'];
        $Estado = $input['Estado'];
        $Cargo = $this->model->find($Id);
    
        if (!$Cargo) {
            return $this->failNotFound('El Cargo no existe');
        }
        $session = \Config\Services::session();
        $id_user = $session->get('id');
        $data = [
            'Nombre' => $Nombre,
            'Estado' => $Estado,
            'id_user' => $id_user,
        ];
        if (!$this->model->update($Id, $data)) {
            return $this->failServerError('No se pudo actualizar el Cargo');
        }    
        return $this->respond(['success' => 'Cargo actualizado con éxito']);
    }    
    public function delete($id = null){
        $model = new CargoModelo();
        $Cargo = $model->find($id);
        if (!$Cargo) {
            return $this->failNotFound('El Cargo no existe');
        }
        if ($model->delete($id)) {
            $deletedCargo = $model->withDeleted()->find($id);
            if ($deletedCargo['deleted_at']) {
                return $this->respondDeleted([
                    'message' => 'Cargo eliminado con éxito',
                    'deleted_at' => $deletedCargo['deleted_at']
                ]);
            } else {
                return $this->failServerError('El campo "deleted_at" no se llenó correctamente');
            }
        } else {
            return $this->failServerError('No se pudo eliminar el Cargo');
        }
    }   
}
