<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\PaginaModelo;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;

class Pagina extends BaseController{
    use ResponseTrait;
    protected $model;
    protected $sucursal;
    public function __construct(){
        $this->model = new PaginaModelo();
    }
    public function index():ResponseInterface{
        $session = session();
        $userId = $session->get('id');
            if (!$userId) {
            return $this->failUnauthorized('Usuario no autenticado.');
        }
        $Paginas = $this->model->findAll();
        $response = ['data' => $Paginas];
        return $this->respond($response, ResponseInterface::HTTP_OK);
    }
    public function store(){
        $data = $this->request->getPost();    
        if (!empty($data)) {
            $model = new PaginaModelo();
            $model->insert($data);
            return $this->respondCreated(['success' => true]);
        } else {
            return $this->failValidationError('No se recibieron datos para insertar.');
        }
    }
}