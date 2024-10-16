<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\CargoModelo;
use App\Models\SucursalModelo;
use App\Models\UserModelo;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;

class Usuario extends BaseController{
    use ResponseTrait; 
    protected $model;
    protected $sucursal;
    protected $Cargo;
    protected $session;
    public function __construct(){
        $this->model = new UserModelo();
        $this->sucursal = new SucursalModelo();
        $this->Cargo = new CargoModelo();
        $this->session = \Config\Services::session();
    }
    public function vista(){
        return view('Dashboard/Plantillas/index').view('Dashboard/Plantillas/aside').view('Dashboard/Modulos/Usuario/Usuario').view('Dashboard/Plantillas/footer');
    }
    public function index($id_sucursal): ResponseInterface {
        $session = session();
        $userId = $session->get('id');
            if (!$userId) {
            return $this->failUnauthorized('Usuario no autenticado.');
        }
        $Usuario = $this->model
            ->where('id_sucursal', $id_sucursal)
            ->findAll();
            $Usuario = $this->Cargos($Usuario);
        $response = ['data' => $Usuario];
        return $this->respond($response, ResponseInterface::HTTP_OK);
    }
    public function indexCargo(): ResponseInterface {
        $Cargo = $this->Cargo->findAll();
        $response = ['data' => $Cargo];
        return $this->respond($response, ResponseInterface::HTTP_OK);
    }
    private function Cargos(array $Cargitos): array {
        foreach ($Cargitos as &$trabajador) {
            $idCargo = $trabajador['id_cargo'];
            $Nombre = $this->nombreCargos($idCargo);
            $trabajador['id_cargo'] = [
                'id' => $idCargo,
                'Nombre' => $Nombre
            ];
        }
        return $Cargitos;
    }
    private function nombreCargos(int $nombre): string {
        $Cargo = $this->Cargo->find($nombre);
        return $Cargo ? $Cargo['Nombre'] : 'Sucursal desconocida';
    }
    public function store() {
        $data = $this->request->getPost();
        if (!empty($data)) {
            // Verificar si id_cargo es null o no está presente y asignar el valor predeterminado 4
            if (!isset($data['id_cargo']) || $data['id_cargo'] === null) {
                $data['id_cargo'] = 4;
            }
    
            if (isset($data['clave'])) {
                $data['clave'] = password_hash($data['clave'], PASSWORD_DEFAULT);
            }
            if ($this->session->has('id')) {
                $data['created_by'] = $this->session->get('id');
            } else {
                return $this->failServerError('ID de usuario no disponible en la sesión.');
            }
            if ($this->model->insert($data)) {
                $id = $this->model->getInsertID();
                $createdUsuario = $this->model->find($id);
                unset($createdUsuario['deleted_at']);
                return $this->respondCreated(['success' => true, 'data' => $createdUsuario, 'message' => 'Usuario creado con éxito.']);
            } else {
                return $this->fail($this->model->errors());
            }
        } else {
            return $this->failValidationError('No se recibieron datos para insertar.');
        }
    }    
    public function show($id): ResponseInterface {
        $Usuario = $this->model->find($id);
        if (!$Usuario) {
            return $this->failNotFound('El perfil no existe');
        }
        unset($Usuario['clave']);
        return $this->respond($Usuario, ResponseInterface::HTTP_OK);
    }
    public function update($id): ResponseInterface {
        $input = $this->request->getJSON(true);
        $nombre = $input['nombre'] ?? null;
        $login = $input['login'] ?? null;
        $clave = $input['clave'] ?? null;
        $id_sucursal = $input['id_sucursal'] ?? null;
        $id_cargo = $input['id_cargo'] ?? null;
        $estado = $input['estado'] ?? null;
    
        $Cargo = $this->model->find($id);
        
        if (!$Cargo) {
            return $this->failNotFound('El Cargo no existe');
        }
    
        $data = [
            'nombre' => $nombre,
            'login' => $login,
            'id_sucursal' => $id_sucursal,
            'id_cargo' => $id_cargo,
            'estado' => $estado
        ];
    
        // Si la contraseña se proporciona y no está vacía, se actualiza la contraseña y el estado
        if ($clave !== null && $clave !== '') {
            $data['clave'] = password_hash($clave, PASSWORD_DEFAULT);
            $data['estado'] = 3;
            $data['administrador'] = 0;
        } else {
            // Si no se proporciona una nueva contraseña, se mantiene el estado actual y se ajusta administrador
            $data['estado'] = $Cargo['estado']; // Mantener el estado actual
            $data['administrador'] = ($estado == 3) ? 1 : 0; // Establecer administrador a 1 solo si el estado es 3
        }
    
        $session = session();
        $userId = $session->get('id');
        if (!$userId) {
            return $this->failUnauthorized('Usuario no autenticado.');
        }
    
        $data['updated_by'] = $userId;
    
        if (!$this->model->update($id, $data)) {
            return $this->failServerError('No se pudo actualizar el Cargo');
        }
    
        return $this->respond(['success' => 'Cargo actualizado con éxito']);
    }    
    public function delete($id = null){
        $model = new UserModelo();
        $Cargo = $model->find($id);
        if (!$Cargo) {
            return $this->failNotFound('El Cargo no existe');
        }
        $data['deleted_by'] = $this->session->get('id');
        $model->update($id, $data);
        if ($model->delete($id)) {
            $deletedCargo = $model->withDeleted()->find($id);
            if ($deletedCargo['deleted_at']) {
                return $this->respondDeleted([
                    'message' => 'Cargo eliminado con éxito',
                    'deleted_at' => $deletedCargo['deleted_at']
                ]);
            } else {
                return $this->failServerError('El campo "deleted_at" no se llenó correctamente');
            }
        } else {
            return $this->failServerError('No se pudo eliminar el Cargo');
        }
    }
    public function getUserInfo(){
        $session = session();
        $userId = $session->get('id');
        $cargo = $session->get('Cargo');
        $idSucursal = $session->get('Sucursal');
        $response = [
            'id' => $userId,
            'cargo' => $cargo,
            'id_sucursal' => $idSucursal
        ];
        return $this->response->setJSON($response);
    }
}
