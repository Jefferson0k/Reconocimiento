<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\AsistenciaModelo;
use App\Models\HorarioModelo;
use App\Models\ObservacionesModelo;
use App\Models\TrabajadorModelo;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\Request;
use CodeIgniter\HTTP\ResponseInterface;

class Asistencia extends BaseController{
    use ResponseTrait;
    protected $model;
    public function __construct(){   
        $this->model = new TrabajadorModelo();
    }
    public function index(){
        $session = session();
        $data['Sucursal'] = $session->get('Sucursal'); 
        return view('Dashboard/Reconocimiento/Reconocimiento',$data);
    }
    public function store(){
        date_default_timezone_set('America/Lima');
        $id_trabajador = $this->request->getPost('id_trabajador');
        $id_sucursal = $this->request->getPost('id_sucursal');
        $fecha_actual = date("Y-m-d");
        $hora_actual = date("H:i:s");

        // Modelos
        $asistenciaModel = new AsistenciaModelo();
        $trabajadorModel = new TrabajadorModelo();
        $horarioModel = new HorarioModelo();
        $observacionesModel = new ObservacionesModelo();
        
        $trabajador = $trabajadorModel->find($id_trabajador);
        if (!$trabajador) {
            return $this->respond(['error' => 'Trabajador no encontrado'], 404);
        }

        $id_horario = $trabajador['id_horario'];

        // Obtener el horario del trabajador
        $horario = $horarioModel->find($id_horario);
        if (!$horario) {
            return $this->respond(['error' => 'Horario no encontrado'], 404);
        }

        $ingreso = $horario['ingreso'];
        $salida = $horario['salida'];
        $break_entrada = $horario['break_entrada'];
        $break_salida = $horario['break_salida'];
        $totalHoras = $this->convertToSeconds($horario['totalHoras']);
        $tolerancia_entrada = 5 * 60; // 5 minutos en segundos

        // Calcular la hora límite para registro permitido (ingreso - tolerancia)
        $limite_registro_antes = date("H:i:s", strtotime($ingreso) - $tolerancia_entrada);

        // Verificar si hay observaciones para el trabajador en la fecha actual
        $observaciones = $observacionesModel->where('id_trabajador', $id_trabajador)
                                            ->where('fecha', $fecha_actual)
                                            ->first();

        if ($observaciones) {
            return $this->respond(['error' => 'Asistencia ya fue justificada para hoy.'], 400);
        }

        // Calcular tardanza en minutos
        $tardanza_minutos = $this->calculateTardanza($ingreso, $hora_actual);

        // Obtener el registro existente
        $existing_record = $asistenciaModel->where('id_trabajador', $id_trabajador)
                                            ->where('fecha', $fecha_actual)
                                            ->first();

        if ($existing_record) {
            try {
                if (empty($existing_record['break_inicio'])) {
                    // Registrar inicio de break
                    $existing_record['break_inicio'] = $hora_actual;
                    $asistenciaModel->update($existing_record['id'], $existing_record);
                    return $this->respond(['success' => 'Inicio de break registrado correctamente'], 200);
                } elseif (empty($existing_record['break_final'])) {
                    // Registrar final de break
                    $existing_record['break_final'] = $hora_actual;

                    // Calcular tardanza en break
                    $break_entrada_time = strtotime($break_entrada);
                    $break_final_time = strtotime($hora_actual);
                    $tardanza_break = max($break_final_time - $break_entrada_time - $tolerancia_entrada, 0) / 3600;

                    // Actualizar tardanza break
                    $existing_record['tardanza_break'] = $this->convertToTime($tardanza_break * 3600);

                    $asistenciaModel->update($existing_record['id'], $existing_record);
                    return $this->respond(['success' => 'Final de break registrado correctamente'], 200);
                } elseif (empty($existing_record['hora_salida'])) {
                    // Registrar hora de salida
                    $existing_record['hora_salida'] = $hora_actual;

                    // Calcular horas trabajadas, horas extras y tardanzas
                    $hora_entrada = strtotime($existing_record['hora_entrada']);
                    $break_inicio = strtotime($existing_record['break_inicio']);
                    $break_final = strtotime($existing_record['break_final']);
                    $hora_salida = strtotime($existing_record['hora_salida']);

                    // Horas trabajadas en segundos
                    $horas_trabajadas_segundos = ($hora_salida - $hora_entrada - ($break_final - $break_inicio));
                    $horas_trabajadas = $horas_trabajadas_segundos / 3600;

                    // Calcular horas extras
                    $horas_extras = max($horas_trabajadas_segundos - $totalHoras, 0) / 3600;

                    // Actualizar el registro con horas trabajadas, extras y tardanza
                    $existing_record['horas_trabajadas'] = $this->convertToTime($horas_trabajadas_segundos);
                    $existing_record['horas_extras'] = $this->convertToTime($horas_extras * 3600);

                    // Calcular tardanza en minutos y actualizar
                    $existing_record['horas_tardanza'] = $this->convertToTime($tardanza_minutos * 60);

                    $asistenciaModel->update($existing_record['id'], $existing_record);
                    return $this->respond(['success' => 'Hora de salida registrada correctamente', 'horas_trabajadas' => $existing_record['horas_trabajadas']], 200);
                } else {
                    return $this->respond(['error' => 'Ya se han registrado todos los horarios para este trabajador en la fecha actual'], 400);
                }
            } catch (\Exception $e) {
                return $this->respond(['error' => 'Error al actualizar el registro: ' . $e->getMessage()], 500);
            }
        } else {
            // Validar hora de entrada
            if (strtotime($hora_actual) < strtotime($limite_registro_antes) || strtotime($hora_actual) > strtotime($salida)) {
                return $this->respond(['error' => 'Hora de entrada no está dentro del rango permitido'], 400);
            }

            // Registrar hora de entrada
            $data = [
                'fecha' => $fecha_actual,
                'hora_entrada' => $hora_actual,
                'id_trabajador' => $id_trabajador,
                'id_sucursal' => $id_sucursal,
                'horas_tardanza' => $this->convertToTime($tardanza_minutos * 60) // Convertir tardanza a formato 'HH:MM:SS'
            ];

            try {
                $asistenciaModel->insert($data);
                return $this->respondCreated(['success' => true, 'tardanza' => $data['horas_tardanza']]);
            } catch (\Exception $e) {
                return $this->respond(['error' => 'Error al insertar la asistencia: ' . $e->getMessage()], 500);
            }
        }
    }
    private function convertToSeconds($time) {
        list($hours, $minutes, $seconds) = explode(':', $time);
        return ($hours * 3600) + ($minutes * 60) + $seconds;
    }
    private function convertToTime($seconds) {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $seconds = $seconds % 60;
        return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
    }
    private function calculateTardanza($ingreso, $hora_actual) {
        $ingreso_time = strtotime($ingreso);
        $hora_actual_time = strtotime($hora_actual);

        if ($hora_actual_time > $ingreso_time) {
            return round(($hora_actual_time - $ingreso_time) / 60); // Tardanza en minutos
        } else {
            return 0; 
        }
    }
    public function show($id = null) {
        $foto_data = [];
        $trabajadores = $this->model->findAll();
        foreach ($trabajadores as &$trabajador) {
            $sucursal_id = $trabajador['id_sucursal'];
            $estado = $trabajador['estado'];
            $foto = $trabajador['foto'];
            if ($sucursal_id == $id && $estado == 1 && strpos($foto, 'default') === false) {
                $foto_data[] = [
                    'ruta' => 'public/Trabajadores/Sucursales/' . $id . '/' . $foto,
                    'nombre' => $trabajador['nombres'] . " " . $trabajador['Apellidos'],
                    'idTrabajador' => $trabajador['id'],
                    'id_sucursal' => $trabajador['id_sucursal'],
                    'descriptor' => null,
                ];
            }
        }
        return $this->respond($foto_data, ResponseInterface::HTTP_OK);
    }
}