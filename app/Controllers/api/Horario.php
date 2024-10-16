<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\HorarioModelo;
use App\Models\TurnosModelo;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;
class Horario extends BaseController{

    use ResponseTrait;
    protected $model;
    protected $Turnos;
    public function __construct(){
        $this->model = new HorarioModelo();
        $this->Turnos = new TurnosModelo();
    }
    public function vista(){
        return view('Dashboard/Plantillas/index').view('Dashboard/Plantillas/aside').view('Dashboard/Modulos/Horario/Horario').view('Dashboard/Plantillas/footer');
    }
    public function index($id_sucursal): ResponseInterface {
        $session = session();
        $userId = $session->get('id');
            if (!$userId) {
            return $this->failUnauthorized('Usuario no autenticado.');
        }
        $Horario = $this->model
            ->where('id_sucursal', $id_sucursal)
            ->findAll();
            $Horario = $this->Turno($Horario);
        $response = ['data' => $Horario];
        return $this->respond($response, ResponseInterface::HTTP_OK);
    }
    public function indexEstados($id_sucursal): ResponseInterface {
        $session = session();
        $userId = $session->get('id');
        if (!$userId) {
            return $this->failUnauthorized('Usuario no autenticado.');
        }
        $Horario = $this->model
            ->where('id_sucursal', $id_sucursal)
            ->where('estado', 1)
            ->findAll();
    
        $Horario = $this->Turno($Horario);
        $response = ['data' => $Horario];
        return $this->respond($response, ResponseInterface::HTTP_OK);
    }    
    public function indexTurnos(): ResponseInterface {
        $Turnos = $this->Turnos->findAll();
        $response = ['data' => $Turnos];
        return $this->respond($response, ResponseInterface::HTTP_OK);
    }
    private function Turno(array $Truenos): array {
        foreach ($Truenos as &$Truno) {
            $idTruno = $Truno['id_Turnos'];
            $nombre = $this->nombreTurno($idTruno);
            $Truno['id_Turnos'] = [
                'id' => $idTruno,
                'Turno' => $nombre
            ];
        }
        return $Truenos;
    }
    private function nombreTurno(int $nombre): string {
        $Turnos = $this->Turnos->find($nombre);
        return $Turnos ? $Turnos['Turno'] : 'Sucursal desconocida';
    }
    public function store(){
        $data = $this->request->getJSON();    
        if (!empty($data)) {
            $session = session();
            $userId = $session->get('id');
            if (!$userId) {
                return $this->failUnauthorized('Usuario no autenticado.');
            }
            $data->id_user = $userId;
            $model = new HorarioModelo();
            $model->insert((array)$data);    
            return $this->respondCreated(['success' => true]);
        } else {
            return $this->failValidationError('No se recibieron datos para insertar.');
        }
    }        
    public function show($id, $id_sucursal): ResponseInterface {
        $session = session();
        $userId = $session->get('id');
            if (!$userId) {
            return $this->failUnauthorized('Usuario no autenticado.');
        }
        $Horario = $this->model->find($id);
        if (!$Horario) {
            return $this->failNotFound('El Horario no existe');
        }
        if ($Horario['id_sucursal'] != $id_sucursal) {
            return $this->failNotFound('El Horario no pertenece a la sucursal especificada');
        }
        $TurnoModel = new TurnosModelo();
        $turnos = $TurnoModel->where('id_sucursal', $id_sucursal)->findAll();
        if (!$turnos) {
            return $this->failNotFound('No se encontraron turnos asociados a la sucursal');
        }
        $nombres_turnos = [];
        foreach ($turnos as $turno) {
            $nombres_turnos[] = [
                'id' => $turno['id'],
                'Turnos' => $turno['Turno']
            ];
        }
        $Horario['id_Turnos'] = $nombres_turnos;
        return $this->respond($Horario, ResponseInterface::HTTP_OK);
    }
    public function update($id, $id_sucursal): ResponseInterface {
        $input = $this->request->getJSON(true);
        $ingreso = $input['ingreso'];
        $salida = $input['salida'];
        $break_entrada = $input['break_entrada'];
        $break_salida = $input['break_salida'];
        $descripcion = $input['descripcion'];
        $estado = $input['estado'];
        $totalHoras = $input['totalHoras'];
        $id_Turno = $input['id_Turno'];        
        $Horario = $this->model->find($id);
        if (!$Horario) {
            return $this->failNotFound('El Horario no existe');
        }
        if ($Horario['id_sucursal'] != $id_sucursal) {
            return $this->failNotFound('El Horario no pertenece a la sucursal especificada');
        }
        $session = session();
        $id_user = $session->get('id');
        if (!$id_user) {
            return $this->failUnauthorized('Usuario no autenticado.');
        }
        $data = [
            'ingreso' => $ingreso,
            'salida' => $salida,
            'break_entrada'=> $break_entrada,
            'break_salida'=> $break_salida,
            'descripcion'=> $descripcion,
            'estado'=> $estado,
            'totalHoras'=> $totalHoras,
            'id_Turnos' => $id_Turno,
            'id_sucursal' => $id_sucursal,
            'updated_by' => $id_user, 
        ];
        if (!$this->model->update($id, $data)) {
            return $this->failServerError('No se pudo actualizar el Horario');
        }
        return $this->respond(['success' => 'Horario actualizado con éxito']);
    }           
    public function delete($id = null){
        $model = new HorarioModelo();
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
            // Actualizar el registro eliminado con el ID del usuario que lo eliminó
            $data = ['deleted_by' => $id_user];
            $model->update($id, $data);
    
            $deletedHorario = $model->withDeleted()->find($id);
            if ($deletedHorario['deleted_at']) {
                $response = [
                    'message' => 'Horario eliminado con éxito',
                    'deleted_at' => $deletedHorario['deleted_at'],
                    'id_user' => $id_user
                ];
                return $this->respondDeleted($response);
            } else {
                return $this->failServerError('El campo "deleted_at" no se llenó correctamente');
            }
        } else {
            return $this->failServerError('No se pudo eliminar el Horario');
        }
    }      
    public function deleteMultiple() {
        $model = new HorarioModelo();
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
            $horario = $model->find($id);
            if (!$horario) {
                return $this->failNotFound("El Horario con ID $id no existe");
            }
            if (!$model->update($id, ['deleted_by' => $id_user])) {
                return $this->failServerError("No se pudo actualizar el campo deleted_by para el Horario con ID $id");
            }    
            if (!$model->delete($id)) {
                return $this->failServerError("No se pudo eliminar el Horario con ID $id");
            }
    
            $deletedIds[] = $id;
        }
    
        return $this->respondDeleted([
            'message' => 'Horarios eliminados con éxito',
            'deleted_ids' => $deletedIds,
            'id_user' => $id_user
        ]);
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
            $model = new HorarioModelo();
            $successCount = $model->insertDataFromCsv($csv_file->getTempName(), $id_sucursal, $id_user);
            return $this->response->setBody("Se han insertado correctamente $successCount registros.");
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setBody("Error al procesar el archivo CSV: " . $e->getMessage());
        }
    }
    public function getHorariosBySucursal($id_sucursal){
        $horarios = $this->model->where('id_sucursal', $id_sucursal)->findAll();
        return $this->response->setJSON(['data' => $horarios]);
    }
}