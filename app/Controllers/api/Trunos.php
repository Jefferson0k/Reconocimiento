<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\TurnosModelo;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;
class Trunos extends BaseController{
    use ResponseTrait;
    protected $model;
    public function __construct(){
        $this->model = new TurnosModelo();
    }
    public function vista(){
        return view('Dashboard/Plantillas/index').view('Dashboard/Plantillas/aside').view('Dashboard/Modulos/Turno/Turno').view('Dashboard/Plantillas/footer');
    }
    public function index($id_sucursal): ResponseInterface {
        $Turnos = $this->model
            ->where('id_sucursal', $id_sucursal)
            ->findAll();
        $response = ['data' => $Turnos];
        return $this->respond($response, ResponseInterface::HTTP_OK);
    }  
    public function indexE($id_sucursal): ResponseInterface {
        $session = session();
        $userId = $session->get('id');
            if (!$userId) {
            return $this->failUnauthorized('Usuario no autenticado.');
        }
        $Turnos = $this->model
            ->where('id_sucursal', $id_sucursal)
            ->where('estado !=', 0)
            ->findAll();
        $response = ['data' => $Turnos];
        return $this->respond($response, ResponseInterface::HTTP_OK);
    }
    public function store(){
        $data = $this->request->getPost();
        
        if (!empty($data)) {
            $session = session();
            $userId = $session->get('id');
            if (!$userId) {
                return $this->failUnauthorized('Usuario no autenticado.');
            }
            $data['id_user'] = $userId;
            if ($this->model->insert($data)) {
                $id = $this->model->getInsertID();
                $createdTurnos = $this->model->find($id);
                unset($createdTurnos['deleted_at']);
                return $this->respondCreated(['success' => true, 'data' => $createdTurnos, 'message' => 'Turno creado con éxito.']);
            } else {
                return $this->fail($this->model->errors());
            }
        } else {
            return $this->failValidationError('No se recibieron datos para insertar.');
        }
    }
    public function show($id, $id_sucursal){
        try {
            $truno = $this->model->find($id);    
            if (!$truno) {
                return $this->failNotFound('El turno no existe');
            }    
            return $this->respond($truno, ResponseInterface::HTTP_OK);    
        } catch (\Exception $e) {
            return $this->failServerError('Se produjo un error al obtener el turno: ' . $e->getMessage());
        }
    }
    public function update($id, $id_sucursal): ResponseInterface {
        $input = $this->request->getJSON(true);
        if (!isset($input['Turno']) || !isset($input['estado'])) {
            return $this->failValidationError('Faltan campos obligatorios');
        }
        $Turno = $input['Turno'];
        $estado = $input['estado'];
    
        $Turnos = $this->model->find($id);
        if (!$Turnos) {
            return $this->failNotFound('El Turno no existe');
        }
        if ($Turnos['id_sucursal'] != $id_sucursal) {
            return $this->failNotFound('El Turno no pertenece a la sucursal especificada');
        }
        $session = session();
        $userId = $session->get('id');
        if (!$userId) {
            return $this->failUnauthorized('Usuario no autenticado.');
        }
        $data = [
            'Turno' => $Turno,
            'estado' => $estado,
            'updated_by' => $userId,
        ];
    
        if (!$this->model->update($id, $data)) {
            return $this->failServerError('No se pudo actualizar el Turno');
        }    
        return $this->respond(['success' => 'Turno actualizado con éxito']);
    }    
    public function delete($id = null){
        $model = new TurnosModelo();
        $Horario = $model->find($id);    
        if (!$Horario) {
            return $this->failNotFound('El Horario no existe');
        }
        $session = session();
        $id_user = $session->get('id');
        if (!$id_user) {
            return $this->failUnauthorized('Usuario no autenticado.');
        }
        if ($model->delete($id)) {
            $data = ['deleted_by' => $id_user];
            $model->update($id, $data);

            $deletedHorario = $model->withDeleted()->find($id);
            if ($deletedHorario['deleted_at']) {
                $data = [
                    'message' => 'Horario eliminado con éxito',
                    'deleted_at' => $deletedHorario['deleted_at'],
                    'id_user' => $id_user
                ];
                return $this->respondDeleted($data);
            } else {
                return $this->failServerError('El campo "deleted_at" no se llenó correctamente');
            }
        } else {
            return $this->failServerError('No se pudo eliminar el Horario');
        }
    }
    public function upload_csv() {
        $id_sucursal = $this->request->getPost('id_sucursal');
        $session = session();
        $id_user = $session->get('id');
        if (!$id_user) {
            return $this->failUnauthorized('Usuario no autenticado.');
        }
    
        $csv_file = $this->request->getFile('userfile');
        if (!$csv_file->isValid()) {
            return $this->response->setStatusCode(400)->setBody("No se ha seleccionado ningún archivo.");
        }    
    
        $validMimeTypes = ['text/csv', 'application/vnd.ms-excel', 'text/plain'];
        if (!in_array($csv_file->getClientMimeType(), $validMimeTypes)) {
            return $this->response->setStatusCode(400)->setBody("El archivo seleccionado no es un archivo CSV válido.");
        }
    
        try {
            $model = new TurnosModelo();
            $successCount = $model->insertDataFromCsv($csv_file->getTempName(), $id_sucursal, $id_user);
            return $this->response->setBody("Se han insertado correctamente $successCount registros.");
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setBody("Error al procesar el archivo CSV: " . $e->getMessage());
        }
    }
    public function deleteMultiple(){
        $model = new TurnosModelo();
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
        foreach ($ids as $id) {
            $Turnos = $model->find($id);
            if (!$Turnos) {
                return $this->failNotFound("El Turnos con ID $id no existe");
            }
            if (!$model->update($id, ['deleted_by' => $id_user])) {
                return $this->failServerError("No se pudo actualizar el campo deleted_by para el Horario con ID $id");
            }
            if (!$model->delete($id)) {
                return $this->failServerError("No se pudo eliminar el Turnos con ID $id");
            }

            $deletedIds[] = $id;
        }
        return $this->respondDeleted([
            'message' => 'Turnos eliminados con éxito',
            'deleted_ids' => $deletedIds,
            'id_user' => $id_user
        ]);
    }
}