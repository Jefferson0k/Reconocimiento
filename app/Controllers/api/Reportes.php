<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\AsistenciaModelo;
use App\Models\HorarioModelo;
use App\Models\TrabajadorModelo;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;

class Reportes extends BaseController{
    use ResponseTrait;
    protected $model;
    protected $Trabajador;
    protected $Horario;
    public function __construct(){
        $this->model = new AsistenciaModelo();
        $this->Trabajador = new TrabajadorModelo();
        $this->Horario = new HorarioModelo();

    }
    public function vista(){
        return view('Dashboard/Plantillas/index').view('Dashboard/Plantillas/aside').view('Dashboard/Modulos/Reportes/Reportes').view('Dashboard/Plantillas/footer');
    }
    public function index($id_sucursal): ResponseInterface {
        if ($this->request->getMethod() === 'post') {
            $fechaInicio = $this->request->getPost('fecha_inicio');
            $fechaFin = $this->request->getPost('fecha_fin');
            $idTrabajador = $this->request->getPost('id_trabajador');
        } else {
            $fechaInicio = $this->request->getVar('fecha_inicio');
            $fechaFin = $this->request->getVar('fecha_fin');
            $idTrabajador = $this->request->getVar('id_trabajador');
        }
        
        if (empty($fechaInicio) || empty($fechaFin) || empty($id_sucursal) || empty($idTrabajador)) {
            return $this->failValidationError('Los campos fecha_inicio, fecha_fin, id_sucursal y id_trabajador son requeridos.');
        }
        
        $query = $this->model->where('id_sucursal', $id_sucursal)
                             ->where('id_trabajador', $idTrabajador);
        
        if ($fechaInicio && $fechaFin) {
            $query->where('fecha >=', $fechaInicio)
                  ->where('fecha <=', $fechaFin);
        }
        
        $asistencia = $query->findAll();
        $asistencia = $this->Trabajador($asistencia);
    
        // Ordenar alfabéticamente por NombreCompleto
        usort($asistencia, function($a, $b) {
            return strcmp($a['id_trabajador']['NombreCompleto'], $b['id_trabajador']['NombreCompleto']);
        });
    
        $response = ['data' => $asistencia];
        return $this->respond($response, ResponseInterface::HTTP_OK);
    }    
    public function indexTrabajador(): ResponseInterface {
        $Trabajador = $this->Trabajador->findAll();
        
        // Ordenar alfabéticamente por NombreCompleto
        usort($Trabajador, function($a, $b) {
            $nombreCompletoA = $a['Apellidos'] . ' ' . $a['nombres'];
            $nombreCompletoB = $b['Apellidos'] . ' ' . $b['nombres'];
            return strcmp($nombreCompletoA, $nombreCompletoB);
        });
    
        // Agregar NombreCompleto
        foreach ($Trabajador as &$trab) {
            $trab['NombreCompleto'] = $trab['Apellidos'] . ' ' . $trab['nombres'];
        }
    
        $response = ['data' => $Trabajador];
        return $this->respond($response, ResponseInterface::HTTP_OK);
    }    
    private function Trabajador(array $Trababajadores): array {
        foreach ($Trababajadores as &$Trabajador) {
            $idTrabajador = $Trabajador['id_trabajador'];
            $TrabajadorDetalles = $this->ObtenerTrabajadorDetalles($idTrabajador);
            $Trabajador['id_trabajador'] = [
                'id' => $idTrabajador,
                'NombreCompleto' => $TrabajadorDetalles['Apellidos'] . ' ' . $TrabajadorDetalles['nombres'],
                'dni' => $TrabajadorDetalles['dni']
            ];
        }
        return $Trababajadores;
    }    
    private function ObtenerTrabajadorDetalles(int $idTrabajador): array {
        $Trabajador = $this->Trabajador->find($idTrabajador);
        if ($Trabajador) {
            return [
                'nombres' => $Trabajador['nombres'],
                'Apellidos' => $Trabajador['Apellidos'],
                'dni' => $Trabajador['dni']
            ];
        } else {
            return [
                'nombres' => 'nombres desconocido',
                'Apellidos' => 'Apellidos desconocida',
                'dni' => 'dni desconocido'
            ];
        }
    }
    public function indexGeneral($id_sucursal): ResponseInterface {
        $request = $this->request->getPost();
        $fecha_inicio = $request['fecha_inicio'] ?? null;
        $fecha_fin = $request['fecha_fin'] ?? null;
    
        if (!empty($fecha_inicio) && !empty($fecha_fin)) {
            $trabajadores = $this->Trabajador
                                ->where('id_sucursal', $id_sucursal)
                                ->findAll();
    
            $data = [];
            foreach ($trabajadores as $trabajador) {
                $idTrabajador = $trabajador['id'];
                $nombreCompleto = $trabajador['Apellidos'] . ' ' . $trabajador['nombres'];
    
                // Obtener la asistencia del trabajador en el rango de fechas
                $asistencia = $this->model
                                   ->where('id_trabajador', $idTrabajador)
                                   ->where('fecha >=', $fecha_inicio)
                                   ->where('fecha <=', $fecha_fin)
                                   ->findAll();
    
                // Obtener el horario del trabajador
                $horario = $this->Horario
                                ->where('id', $trabajador['id_horario'])
                                ->first();
                $horasEsperadasPorDia = $horario ? $this->convertirTiempoADecimal($horario['totalHoras']) : 0;
    
                // Inicializar contadores
                $diasLaborados = 0;
                $diasNoLaborados = 0;
                $totalHorasTrabajadas = 0;
                $totalHorasExtras = 0;
                $totalHorasTardanzas = 0;
                $totalTardanzaBreak = 0;
                $totalHorasNoTrabajadas = 0;
    
                $fechaActual = strtotime($fecha_inicio);
                $fechaFin = strtotime($fecha_fin);
    
                while ($fechaActual <= $fechaFin) {
                    $fechaStr = date('Y-m-d', $fechaActual);
                    $laborado = false;
                    $horasTrabajadas = 0;
                    $tardanzaBreak = 0;
                    foreach ($asistencia as $a) {
                        if ($a['fecha'] == $fechaStr) {
                            $laborado = true;
                            $horasTrabajadas = $this->convertirTiempoADecimal($a['horas_trabajadas']);
                            $horasExtras = $this->convertirTiempoADecimal($a['horas_extras']);
                            $horasTardanzas = $this->convertirTiempoADecimal($a['horas_tardanzas']);
                            $tardanzaBreak = $this->calcularTardanzaBreak($a['tardanza_break'], $horario['break_entrada'], $horario['break_salida']);
    
                            $totalHorasTrabajadas += $horasTrabajadas;
                            $totalHorasExtras += $horasExtras;
                            $totalHorasTardanzas += $horasTardanzas;
                            $totalTardanzaBreak += $tardanzaBreak;
                            break;
                        }
                    }
                    if ($laborado) {
                        $diasLaborados++;
                        $totalHorasNoTrabajadas += max(0, $horasEsperadasPorDia - $horasTrabajadas);
                    } else {
                        $diasNoLaborados++;
                        $totalHorasNoTrabajadas += $horasEsperadasPorDia;
                    }
                    $fechaActual = strtotime('+1 day', $fechaActual);
                }
    
                $data[] = [
                    'id' => $idTrabajador,
                    'NombreCompleto' => $nombreCompleto,
                    'dias_laborados' => $diasLaborados,
                    'dias_no_laborados' => $diasNoLaborados,
                    'total_horas_trabajadas' => round($totalHorasTrabajadas, 2),
                    'total_horas_extras' => round($totalHorasExtras, 2),
                    'total_horas_tardanzas' => round($totalHorasTardanzas, 2),
                    'total_tardanza_break' => round($totalTardanzaBreak, 2),
                    'total_horas_no_trabajadas' => round($totalHorasNoTrabajadas, 2)
                ];
            }
    
            usort($data, function($a, $b) {
                return strcmp($a['NombreCompleto'], $b['NombreCompleto']);
            });
    
            $response = ['data' => $data];
            return $this->respond($response, ResponseInterface::HTTP_OK);
        } else {
            $response = ['error' => 'Las fechas de inicio y fin son obligatorias'];
            return $this->respond($response, ResponseInterface::HTTP_BAD_REQUEST);
        }
    }
    private function convertirTiempoADecimal($tiempo) {
        $partes = explode(':', $tiempo);
        if (count($partes) < 3) {
            // Manejo de error
            return 0;
        }
        list($horas, $minutos, $segundos) = $partes;
        return $horas + ($minutos / 60) + ($segundos / 3600);
    }
    private function convertirDecimalATiempo($decimal) {
        $horas = floor($decimal);
        $minutos = floor(($decimal - $horas) * 60);
        $segundos = floor((($decimal - $horas) * 60 - $minutos) * 60);
        return sprintf('%02d:%02d:%02d', $horas, $minutos, $segundos);
    }
    private function calcularTardanzaBreak($tardanza, $breakEntrada, $breakSalida) {
        $tardanzaDecimal = $this->convertirTiempoADecimal($tardanza);
        $breakEntradaDecimal = $this->convertirTiempoADecimal($breakEntrada);
        $breakSalidaDecimal = $this->convertirTiempoADecimal($breakSalida);
    
        // Calcular la duración esperada del break
        $duracionBreakEsperada = $breakSalidaDecimal - $breakEntradaDecimal;
    
        // Calcular la tardanza
        if ($tardanzaDecimal > $duracionBreakEsperada) {
            return $tardanzaDecimal - $duracionBreakEsperada;
        } else {
            return 0;
        }
    }       
    public function getTrabajadoresBySucursal($id_sucursal){
        $trabajadores = $this->model->where('id_sucursal', $id_sucursal)->findAll();
        return $this->response->setJSON(['data' => $trabajadores]);
    }
}