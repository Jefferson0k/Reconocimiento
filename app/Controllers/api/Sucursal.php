<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\SucursalModelo;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;
class Sucursal extends BaseController{
    use ResponseTrait;
    protected $model;
    protected $doseveSucursales;
    public function __construct(){
        $this->model = new SucursalModelo();
    }
    public function Vista(){
        return view('Dashboard/Plantillas/index').view('Dashboard/Plantillas/aside').view('Dashboard/Modulos/Sucursales/Sucursales').view('Dashboard/Plantillas/footer');
    }
    public function index(): ResponseInterface {
        $session = session();
        $userId = $session->get('id');
            if (!$userId) {
            return $this->failUnauthorized('Usuario no autenticado.');
        }
        $Sucursal = $this->model->findAll();
        $response = ['data' => $Sucursal];
        return $this->respond($response, ResponseInterface::HTTP_OK);
    }    
    public function show($id): ResponseInterface {
        $session = session();
        $userId = $session->get('id');
            if (!$userId) {
            return $this->failUnauthorized('Usuario no autenticado.');
        }
        $Sucursal = $this->model->find($id);
        if (!$Sucursal) {
            return $this->failNotFound('La Sucursal no existe');
        }
        return $this->respond($Sucursal, ResponseInterface::HTTP_OK);
    }
    public function update($id): ResponseInterface {
        $input = $this->request->getJSON(true);
        $nombre = $input['nombre'];
        $direccion = $input['direccion'];
        $estado = $input['estado'];
        $Sucursal = $this->model->find($id);
        if (!$Sucursal) {
            return $this->failNotFound('La Sucursal no existe');
        }
        $session = session();
        $userId = $session->get('id');
        if (!$userId) {
            return $this->failUnauthorized('Usuario no autenticado.');
        }
        $data = [
            'nombre' => $nombre,
            'direccion' => $direccion,
            'estado' => $estado,
            'updated_by' => $userId,
        ];
        if (!$this->model->update($id, $data)) {
            return $this->failServerError('No se pudo actualizar la Sucursal');
        }
        return $this->respond(['success' => 'Sucursal actualizada con éxito']);
    }
    public function store(): ResponseInterface {
        $session = session();
        $userId = $session->get('id');
        if (!$userId) {
            return $this->failUnauthorized('Usuario no autenticado.');
        }
        $data = [ 
            'direccion' => $this->request->getPost('direccion'),
            'nombre' => $this->request->getPost('nombre'),
            'estado' => $this->request->getPost('estado') == '1' ? 1 : 0,
            'id_user' => $userId 
        ];    
        $validation = \Config\Services::validation();
        $validation->setRules((new \Config\Validation())->doseveSucursales);
        if (!$validation->run($data)) {
            return $this->failValidationErrors($validation->getErrors());
        }
    
        try {
            $rutaPrincipal = ROOTPATH . 'public/Trabajadores/Sucursales/';
            if (!is_dir($rutaPrincipal)) {
                mkdir($rutaPrincipal, 0755, true);
            }
            
            $idSucursal = $this->model->insert($data);
            $rutaCarpetaSucursal = $rutaPrincipal . $idSucursal . '/';
            if (!is_dir($rutaCarpetaSucursal)) {
                mkdir($rutaCarpetaSucursal, 0755, true);
            }
    
            if ($imagenSucursal = $this->request->getFile('nombre')) {
                $nombreArchivo = $imagenSucursal->getRandomName();
                $imagenSucursal->move($rutaCarpetaSucursal, $nombreArchivo);
                $data['nombre'] = $nombreArchivo;
            }
    
            if ($idSucursal) {
                $confirmationData = [
                    'success' => true,
                    'message' => 'La sucursal se ha creado con éxito.',
                    'id_sucursal' => $idSucursal,
                    'data' => $data
                ];
                return $this->response->setJSON($confirmationData);
            } else {
                return $this->failValidationErrors($this->model->validation->listErrors());
            }
        } catch (\Exception $th) {
            return $this->failServerError('Error en el servidor: ' . $th->getMessage());
        }
    }        
    public function delete($id): ResponseInterface {
        try {
            if ($id === null) {
                return $this->failValidationErrors('Falta el ID');
            }
    
            $session = session();
            $id_user = $session->get('id');
            if (!$id_user) {
                return $this->failUnauthorized('Usuario no autenticado.');
            }
    
            $idSucursal = $id;
            $rutaPrincipal = ROOTPATH . 'public/Trabajadores/Sucursales/';
            $rutaCarpeta = $rutaPrincipal . $idSucursal . '/';
            $this->model->update($idSucursal, ['deleted_by' => $id_user]);
    
            if (is_dir($rutaCarpeta)) {
                $this->deleteDirectoryContents($rutaCarpeta);
                rmdir($rutaCarpeta);
            }
    
            if ($this->model->delete($idSucursal)) {
                $deletedHorario = $this->model->withDeleted()->find($idSucursal);
                if ($deletedHorario) {
                    $data = [
                        'message' => 'Horario eliminado con éxito',
                        'deleted_at' => $deletedHorario['deleted_at'],
                        'deleted_by' => $deletedHorario['deleted_by']
                    ];
                    return $this->respondDeleted($data);
                } else {
                    return $this->failServerError('El campo "deleted_at" no se llenó correctamente');
                }
            } else {
                return $this->failServerError('Error al eliminar el registro de la base de datos');
            }
        } catch (\Exception $e) {
            return $this->failServerError('Error en el servidor: ' . $e->getMessage());
        }
    }    
    private function deleteDirectoryContents($dirPath) {
        if (!is_dir($dirPath)) {
            return false;
        }
        $files = array_diff(scandir($dirPath), ['.', '..']);
        foreach ($files as $file) {
            $filePath = $dirPath . DIRECTORY_SEPARATOR . $file;
            if (is_dir($filePath)) {
                $this->deleteDirectoryContents($filePath);
                rmdir($filePath);
            } else {
                unlink($filePath);
            }
        }
        return true;
    }      
    public function upload_csv(): ResponseInterface {
        $session = session();
        $userId = $session->get('id');
        if (!$userId) {
            return $this->failUnauthorized('Usuario no autenticado.');
        }
    
        $response = [
            'success' => false,
            'message' => ''
        ];
    
        if ($this->request->getFile('userfile')) {
            $csv = $this->request->getFile('userfile');
            if ($csv->isValid() && $csv->getExtension() == 'csv') {
                $file_path = $csv->getTempName();
    
                $model = new SucursalModelo();
                $model->insertDataFromCsv($file_path, $userId);
    
                $response['success'] = true;
                $response['message'] = 'El archivo CSV se ha subido y procesado correctamente.';
            } else {
                $response['message'] = 'El archivo no es válido o no es un archivo CSV.';
            }
        } else {
            $response['message'] = 'No se ha seleccionado ningún archivo CSV.';
        }
    
        return $this->response->setJSON($response);
    }          
    public function getUserById($id): ResponseInterface {
        $user = $this->model->find($id);    
        if (!$user) {
            return $this->respond(['message' => 'Usuario no encontrado'], ResponseInterface::HTTP_NOT_FOUND);
        }
        $nombre = preg_replace('/[^a-zA-Z]/', '', $user['nombre']);
        $nombre = strtoupper(substr($nombre, 0, 3));
        $direccion = preg_replace('/[^.]*\./', '', $user['direccion']);
        $direccion = preg_replace('/[^a-zA-Z0-9]/', '', $direccion);
        $direccion = strtoupper(substr($direccion, 0, 4));
        $numeroAleatorio = rand(1000, 9999);
        $usuarioConCodigo = $nombre . $direccion . $numeroAleatorio;    
        return $this->respond(['data' => $usuarioConCodigo], ResponseInterface::HTTP_OK);
    }
    public function DeleteMultiple(): ResponseInterface {
        try {
            $model = new SucursalModelo();
            $ids = $this->request->getJSON(true)['ids'];
            
            if (!is_array($ids)) {
                return $this->failValidationError('Se esperaba un array de IDs');
            }            
            $session = session();
            $id_user = $session->get('id');
            if (!$id_user) {
                return $this->failUnauthorized('Usuario no autenticado.');
            }    
            $deletedIds = [];
            $errores = [];            
            foreach ($ids as $id) {

                $Sucursal = $model->withDeleted()->find($id);
                if (!$Sucursal) {
                    $errores[] = "El Sucursal con ID $id no existe";
                    continue;
                }    

                $model->update($id, ['deleted_by' => $id_user]);    

                $rutaPrincipal = ROOTPATH . 'public/Trabajadores/Sucursales/';
                $rutaCarpeta = $rutaPrincipal . $id . '/';    

                if (is_dir($rutaCarpeta)) {
                    $this->deleteDirectoryContents($rutaCarpeta);
                    rmdir($rutaCarpeta);
                }    

                if ($model->delete($id)) {
                    $deletedIds[] = $id;
                } else {
                    $errores[] = "No se pudo eliminar el Sucursal con ID $id";
                }
            }
            $response = [
                'message' => 'Sucursal(es) eliminados con éxito',
                'deleted_ids' => $deletedIds,
                'id_user' => $id_user
            ];    
            if (!empty($errores)) {
                $response['errors'] = $errores;
            }
            return $this->respondDeleted($response);
        } catch (\Exception $e) {
            return $this->failServerError('Error en el servidor: ' . $e->getMessage());
        }
    }
    public function getSucursalUsuario(){
        $session = session();
        $idSucursal = $session->get('Sucursal');

        return $this->response->setJSON(['id_sucursal' => $idSucursal]);
    }
    public function getSucursales(){
        $sucursales = $this->model->findAll();
        return $this->response->setJSON(['data' => $sucursales]);
    }
}
