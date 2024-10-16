<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\AccesosModelo;
use App\Models\PaginaModelo;
use App\Models\UserModelo;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;
class Accesos extends BaseController{
    use ResponseTrait;
    protected $model;
    protected $User;
    protected $Pagina;
    public function __construct(){
        $this->model = new AccesosModelo();
        $this->User = new UserModelo();
        $this->Pagina = new PaginaModelo();
    }
    public function index(): ResponseInterface {
        $Accesos = $this->model->findAll();
        $response = ['data' => $Accesos];
        return $this->respond($response, ResponseInterface::HTTP_OK);
    }
    public function store(){
        $data = $this->request->getPost();    
        if (!empty($data)) {
            $model = new AccesosModelo();
            $model->insert($data);
            return $this->respondCreated(['success' => true]);
        } else {
            return $this->failValidationError('No se recibieron datos para insertar.');
        }
    }
    public function show($Id){
        $Acceso = $this->model->find($Id);
        if (!$Acceso) {
            return $this->failNotFound('El acceso no existe');
        }
        return $this->respond($Acceso, ResponseInterface::HTTP_OK);
    }
    public function update($Id): ResponseInterface {
        $estado = $this->request->getPost('estado');
        $id_user = $this->request->getPost('id_user ');
        $id_pagina  = $this->request->getPost('id_pagina');
        $Cargo = $this->model->find($Id);
        if (!$Cargo) {
            return $this->failNotFound('El acceso no existe');
        }
        $data = [
            'estado' => $estado,
            'id_user' => $id_user,
            'id_pagina ' => $id_pagina ,
        ];
        if (!$this->model->update($Id, $data)) {
            return $this->failServerError('No se pudo actualizar el Acceso');
        }
        return $this->respond(['message' => 'Acceso actualizada con éxito']);
    }
    public function delete($id): ResponseInterface{
        $Acceso = $this->model->find($id);
        if (!$Acceso) {
            return $this->failNotFound('El Acceso no existe');
        }
        if (!$this->model->delete($id)) {
            return $this->failServerError('No se pudo eliminar el Acceso');
        }
        return $this->respondDeleted(['message' => 'Acceso eliminado con éxito']);
    }
}
