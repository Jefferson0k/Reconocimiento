<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\AccesosModelo;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\Request;
use CodeIgniter\HTTP\ResponseInterface;

class Permisos extends BaseController{
    use ResponseTrait;
    protected $model;
    public function __construct(){
        $this->model = new AccesosModelo();
    }
    public function vista(){
        return view('Dashboard/Plantillas/index').view('Dashboard/Plantillas/aside').view('Dashboard/Modulos/Permisos/Permisos').view('Dashboard/Plantillas/footer');
    }
    public function index(){
        $session = session();
        $userId = $session->get('id');
            if (!$userId) {
            return $this->failUnauthorized('Usuario no autenticado.');
        }
        $Permisos = $this->model->findAll();
        $response = ['data' => $Permisos];
        return $this->respond($response, ResponseInterface::HTTP_OK);
    }
    public function store(Request $request){

    }
    public function show($id){

    }
    
    public function update(Request $request, $id){

    }
    public function destroy($id){

    }
}
