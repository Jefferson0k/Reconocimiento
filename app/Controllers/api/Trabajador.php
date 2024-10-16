<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\HorarioModelo;
use App\Models\SucursalModelo;
use App\Models\TrabajadorModelo;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;

class Trabajador extends BaseController{
    
    use ResponseTrait;
    protected $model;
    protected $sucursal;
    protected $Horario;
    public function __construct(){
        $this->model = new TrabajadorModelo();
        $this->sucursal = new SucursalModelo();
        $this->Horario = new HorarioModelo();
    }
    public function vista(){
        return view('Dashboard/Plantillas/index').view('Dashboard/Plantillas/aside').view('Dashboard/Modulos/Trabajador/Trabajador').view('Dashboard/Plantillas/footer');
    }
    public function index($id_sucursal): ResponseInterface {
        $session = session();
        $userId = $session->get('id');
            if (!$userId) {
            return $this->failUnauthorized('Usuario no autenticado.');
        }
        $trabajadoresDeSucursal = $this->model
            ->where('id_sucursal', $id_sucursal)
            ->findAll();
            $trabajadoresDeSucursal = $this->Sucursales($trabajadoresDeSucursal);
            $trabajadoresDeSucursal = $this->Horario($trabajadoresDeSucursal);
        $response = ['data' => $trabajadoresDeSucursal];
        return $this->respond($response, ResponseInterface::HTTP_OK);
    }
    public function indexEstado($id_sucursal): ResponseInterface {
        $session = session();
        $userId = $session->get('id');
        
        if (!$userId) {
            return $this->failUnauthorized('Usuario no autenticado.');
        }
        $trabajadoresDeSucursal = $this->model
            ->where('id_sucursal', $id_sucursal)
            ->where('estado', 1)
            ->findAll();
        $trabajadoresDeSucursal = $this->Sucursales($trabajadoresDeSucursal);
        $trabajadoresDeSucursal = $this->Horario($trabajadoresDeSucursal);
    
        $response = ['data' => $trabajadoresDeSucursal];
        return $this->respond($response, ResponseInterface::HTTP_OK);
    }
    public function indexSucursal(): ResponseInterface {
        $Sucursal = $this->sucursal->findAll();
        $response = ['data' => $Sucursal];
        return $this->respond($response, ResponseInterface::HTTP_OK);
    }
    public function indexHorarios(): ResponseInterface {
        $Horario = $this->Horario->findAll();
        $response = ['data' => $Horario];
        return $this->respond($response, ResponseInterface::HTTP_OK);
    }
    private function Sucursales(array $Trabajadores): array {
        foreach ($Trabajadores as &$trabajador) {
            $idSucursal = $trabajador['id_sucursal'];
            $nombre = $this->nombreSucursasles($idSucursal);
            $trabajador['id_sucursal'] = [
                'id' => $idSucursal,
                'nombre' => $nombre
            ];
        }
        return $Trabajadores;
    }
    private function nombreSucursasles(int $nombre): string {
        $sucursal = $this->sucursal->find($nombre);
        return $sucursal ? $sucursal['nombre'] : 'Sucursal desconocida';
    }
    private function Horario(array $Horarios): array {
        foreach ($Horarios as &$Horario) {
            $idHorario = $Horario['id_horario'];
            $horarioDetalles = $this->ObtenerHorarioDetalles($idHorario);
            $Horario['id_horario'] = [
                'id' => $idHorario,
                'ingreso' => $horarioDetalles['ingreso'],
                'salida' => $horarioDetalles['salida'],
                'totalHoras' => $horarioDetalles['totalHoras']
            ];
        }
        return $Horarios;
    }
    private function ObtenerHorarioDetalles(int $idHorario): array {
        $Horario = $this->Horario->find($idHorario);
        if ($Horario) {
            return [
                'ingreso' => $Horario['ingreso'],
                'salida' => $Horario['salida'],
                'totalHoras' => $Horario['totalHoras']
            ];
        } else {
            return [
                'ingreso' => 'ingreso desconocido',
                'salida' => 'salida desconocida',
                'totalHoras' => 'totalHoras desconocida'
            ];
        }
    }    
    public function show($id, $id_sucursal): ResponseInterface {
        $trabajador = $this->model
            ->where('id_sucursal', $id_sucursal)
            ->find($id);
        if (!$trabajador) {
            return $this->failNotFound('El Trabajador no existe en la sucursal especificada');
        }
        $response = ['data' => $trabajador];
        return $this->respond($response, ResponseInterface::HTTP_OK);
    }
    public function store() {
        try {
            $session = \Config\Services::session();
            $id_user = $session->get('id');
            $data = [
                'dni' => $this->request->getPost('dni'),
                'nombres' => $this->request->getPost('nombres'),
                'Apellidos' => $this->request->getPost('Apellidos'),
                'telefono' => $this->request->getPost('telefono'),
                'id_sucursal' => $this->request->getPost('id_sucursal'),
                'id_horario' => $this->request->getPost('id_horario'),
                'estado' => $this->request->getPost('estado'),
                'id_user' => $id_user,
            ];
    
            $imagen = $this->request->getFile('foto');
            $rutaSucursal = ROOTPATH . 'public/Trabajadores/Sucursales/' . $data['id_sucursal'] . '/';
            if (!is_dir($rutaSucursal)) {
                mkdir($rutaSucursal, 0755, true);
            }
    
            if ($imagen && $imagen->isValid() && $imagen->hasMoved() == false) {
                // Validar tipo y tamaño de archivo (opcional)
                $nombreImagen = $imagen->getRandomName();
                $imagen->move($rutaSucursal, $nombreImagen);
                $data['foto'] = $nombreImagen;
            } else {
                $rutaImagenPorDefecto = ROOTPATH . 'public/Trabajadores/SinFoto/default.jpg';
                $nombreImagenPorDefecto = uniqid() . '.jpg';
                copy($rutaImagenPorDefecto, $rutaSucursal . $nombreImagenPorDefecto);
                $data['foto'] = $nombreImagenPorDefecto;
            }
    
            if ($this->model->insert($data)) {
                return 'Agregado con éxito';
            } else {
                return $this->failValidationErrors($this->model->validation->listErrors());
            }
        } catch (\Exception $th) {
            log_message('error', 'Error en store: ' . $th->getMessage());
            return $this->failServerError('Error en el servidor: ' . $th->getMessage());
        }
    }
    public function update($id, $id_sucursal): ResponseInterface {
        // Obtener los datos de la solicitud
        $input = $this->request->getPost();
        $foto = $this->request->getFile('foto');
        $trabajador = $this->model->find($id);
    
        // Verificar si el trabajador existe
        if (!$trabajador) {
            return $this->failNotFound('El Trabajador no existe');
        }
    
        // Ruta base para guardar la imagen
        $basePath = ROOTPATH . 'public/Trabajadores/Sucursales/' . $id_sucursal . '/';
    
        // Si hay una nueva foto, procesarla
        if ($foto && $foto->isValid() && !$foto->hasMoved()) {
            // Crear el directorio si no existe
            if (!is_dir($basePath)) {
                mkdir($basePath, 0777, true);
            }
    
            // Eliminar la foto antigua si existe
            $oldPhotoPath = $basePath . $trabajador['foto'];
            if (file_exists($oldPhotoPath)) {
                unlink($oldPhotoPath);
            }
    
            // Generar un nuevo nombre para la foto y moverla
            try {
                $newPhotoName = time() . '_' . bin2hex(random_bytes(10)) . '.' . $foto->getClientExtension();
                $foto->move($basePath, $newPhotoName);
                $data['foto'] = $newPhotoName;
    
                // Actualizar el campo 'foto' en la base de datos
                $this->model->update($id, ['foto' => $newPhotoName]);
            } catch (\Exception $e) {
                return $this->failServerError('Error al mover la nueva foto: ' . $e->getMessage());
            }
        }
    
        // Actualizar los demás datos del trabajador
        $data = [
            'dni' => $input['dni'],
            'nombres' => $input['nombres'],
            'Apellidos' => $input['Apellidos'],
            'telefono' => $input['telefono'],
            'id_horario' => $input['id_horario'],
            'estado' => $input['estado'],
            'updated_by' => session()->get('id'),
        ];
    
        // Guardar los cambios en la base de datos (excepto 'foto')
        if (!$this->model->update($id, $data)) {
            return $this->failServerError('No se pudo actualizar el Trabajador');
        }
    
        // Responder con éxito
        return $this->respond(['success' => 'Trabajador actualizado con éxito']);
    }   
    public function delete($id): ResponseInterface{
        try {
            if ($id === null) {
                return $this->failValidationErrors('Falta el ID');
            }
            
            $worker = $this->model->find($id);
            if (!$worker) {
                return $this->failNotFound('Trabajador no encontrado');
            }

            $id_sucursal = isset($worker['id_sucursal']) ? $worker['id_sucursal'] : null;
            $rutaFoto = ROOTPATH . 'public/Trabajadores/Sucursales/' . $id_sucursal . '/' . $worker['foto'];

            if (file_exists($rutaFoto)) {
                if (!unlink($rutaFoto)) {
                    return $this->failServerError('No se pudo eliminar la foto del trabajador');
                }
            }

            $result = $this->model->delete($id);
            if ($result) {
                return $this->respondDeleted("Trabajador eliminado", 200);
            } else {
                return $this->failServerError('Error al eliminar el trabajador');
            }
        } catch (\Exception $th) {
            return $this->failServerError('Error en el servidor: ' . $th->getMessage());
        }
    }
    public function upload_csv() {
        $id_user = session()->get('id'); 
    
        $id_sucursal = $this->request->getPost('id_sucursal'); 
        $csv_file = $this->request->getFile('userfile');
    
        if (!$csv_file->isValid()) {
            echo "No se ha seleccionado ningún archivo.";
            return;
        }
    
        if ($csv_file->getClientMimeType() !== 'text/csv') {
            echo "El archivo seleccionado no es un archivo CSV válido.";
            return;
        }
    
        try {
            $model = new TrabajadorModelo();
            $model->insertDataFromCsv($csv_file->getTempName(), $id_sucursal, $id_user);
            echo "Los datos se han insertado correctamente.";
        } catch (\Exception $e) {
            echo "Error al procesar el archivo CSV: " . $e->getMessage();
        }
    }    
    public function generateUsernameById(): ResponseInterface {
        $id = $this->request->getPost('id_trabajador');
        if (!$id) {
            return $this->fail('ID del trabajador no proporcionado en la solicitud', ResponseInterface::HTTP_BAD_REQUEST);
        }
        $trabajador = $this->model->find($id);
        if (!$trabajador) {
            return $this->failNotFound('Trabajador no encontrado');
        }
        $username = $this->generateUsername(
            $trabajador['nombres'],
            $trabajador['Apellidos'],
            $trabajador['dni']
        );
        $response = [
            'id' => $trabajador['id'],
            'username' => $username
        ];
    
        return $this->respond($response, ResponseInterface::HTTP_OK);
    }
    private function generateUsername($nombres, $apellidos, $dni): string {
        $initial = strtoupper(substr($nombres, 0, 1));
        $apellidosArray = explode(' ', $apellidos);
        $primerApellido = strtoupper($apellidosArray[0]);
        $segundoApellido = strtoupper(substr($apellidosArray[1], 0, 2));
        $dniSuffix = substr($dni, -3);
        return $initial . $primerApellido . $segundoApellido . $dniSuffix;
    }
    public function getTrabajadoresBySucursal($id_sucursal){
        $trabajadores = $this->model->where('id_sucursal', $id_sucursal)->findAll();
        return $this->response->setJSON(['data' => $trabajadores]);
    } 
}