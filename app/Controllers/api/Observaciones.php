<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\AsistenciaModelo;
use App\Models\HorarioModelo;
use App\Models\ObservacionesModelo;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;

class Observaciones extends BaseController{
    use ResponseTrait;
    protected $model;
    protected $session;
    protected $horario;
    public function __construct(){
        $this->model = new ObservacionesModelo();
        $this->session = \Config\Services::session();
    }
    public function index():ResponseInterface{
        $session = session();
        $userId = $session->get('id');
            if (!$userId) {
            return $this->failUnauthorized('Usuario no autenticado.');
        }
        $Observaciones = $this->model->findAll();
        $response = ['data' => $Observaciones];
        return $this->respond($response, ResponseInterface::HTTP_OK);
    }
    public function store() {
        $session = \Config\Services::session();
        $idUser = $session->get('id'); // Obtener el ID del usuario autenticado desde la sesión
    
        if (is_null($idUser)) {
            return $this->failUnauthorized('Usuario no autenticado.');
        }
    
        $data = $this->request->getPost();
        $data['id_user'] = $idUser; // Asignar el ID del usuario autenticado
    
        // Validaciones básicas
        if (!isset($data['id_trabajador']) || !isset($data['id_Sucursal']) || !isset($data['fecha'])) {
            return $this->failValidationError('Seleccione un Trabajador y Fecha son requeridos.');
        }
    
        $db = \Config\Database::connect();
        $db->transStart();
    
        try {
            // Inicializar modelos
            $observacionesModel = new ObservacionesModelo();
            $asistenciaModel = new AsistenciaModelo();
            $trabajadorModel = new \App\Models\TrabajadorModelo();
            $horarioModel = new HorarioModelo();
    
            // Verificar si ya existe una observación para el trabajador en la fecha actual
            $existingObservation = $observacionesModel->where('id_trabajador', $data['id_trabajador'])
                                                      ->where('fecha', $data['fecha'])
                                                      ->first();
    
            if ($existingObservation) {
                return $this->failValidationError('Ya existe una observación registrada para este trabajador en esta fecha.');
            }
    
            // Insertar en Observaciones
            $observacionesModel->insert($data);
            $observacionesId = $observacionesModel->insertID();
    
            // Obtener el id_horario del trabajador
            $trabajador = $trabajadorModel->where('id', $data['id_trabajador'])->first();
            if (!$trabajador) {
                return $this->failValidationError('Trabajador no encontrado.');
            }
            $idHorario = $trabajador['id_horario'];
    
            // Obtener el horario del trabajador
            $horario = $horarioModel->where('id', $idHorario)->first();
            if (!$horario) {
                return $this->failValidationError('Horario no encontrado para el id proporcionado.');
            }
    
            // Datos para Asistencia
            $asistenciaData = [
                'fecha' => $data['fecha'],
                'hora_entrada' => $horario['ingreso'],
                'break_inicio' => $horario['break_entrada'],
                'break_final' => $horario['break_salida'],
                'hora_salida' => $horario['salida'],
                'horas_trabajadas' => $horario['totalHoras'], // Mantener como está en formato time (HH:MM:SS)
                'horas_extras' => '00:00:00', // Valor predeterminado en formato time
                'horas_tardanzas' => '00:00:00', // Valor predeterminado en formato time
                'tardanza_break' => '00:00:00', // Valor predeterminado en formato time
                'id_trabajador' => $data['id_trabajador'],
                'id_sucursal' => $data['id_Sucursal'],
                'id_Observaciones' => $observacionesId,
                'estado' => 1, // Establecer siempre a 1
            ];
    
            // Verificar si ya existe un registro de asistencia para el trabajador en la fecha
            $existingRecord = $asistenciaModel->where('id_trabajador', $data['id_trabajador'])
                                              ->where('fecha', $data['fecha'])
                                              ->first();
    
            if ($existingRecord) {
                // Actualizar el registro existente con los datos de la observación
                $asistenciaData['id'] = $existingRecord['id'];
                $asistenciaModel->update($existingRecord['id'], $asistenciaData);
            } else {
                // Insertar un nuevo registro
                $asistenciaModel->insert($asistenciaData);
            }
    
            // Confirmar la transacción
            $db->transComplete();
    
            if ($db->transStatus() === false) {
                throw new \Exception('Error al realizar la transacción');
            }
    
            return $this->respondCreated(['success' => true, 'message' => 'Registro de asistencia creado/actualizado correctamente']);
        } catch (\Exception $e) {
            // Revertir la transacción en caso de error
            $db->transRollback();
            return $this->failServerError('Error al insertar los datos: ' . $e->getMessage());
        }
    }    
}
