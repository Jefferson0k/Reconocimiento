<?php

namespace App\Controllers;

use App\Models\CargoModelo;
use App\Models\ModeloLogin;
use App\Models\UserModelo;

class Login extends BaseController{
    protected $session;
    public function __construct() {
        helper(['form', 'url']);
        $this->session = \Config\Services::session();
    }
    public function index(){
        return view('Login/Login/Login');
    }
    public function Recuperacion(){
        return view('Login/Recuperacion/Recuperacion');
    }
    public function leerdatos() {
        if ($this->session->has('usuario')) {
            $cargoModel = new CargoModelo();
            $cargoId = $this->session->get('Cargo');
            $cargoNombre = $cargoModel->getNombreById($cargoId);
    
            echo 'ID de Usuario: ' . $this->session->get('id') . '<br/>';
            echo 'Usuario: ' . $this->session->get('usuario'). '<br/>';
            echo 'Cargo: ' . $cargoNombre . '<br/>';
            echo 'Id de Sucursal: ' . $this->session->get('Sucursal') . '<br/>';
            echo 'Nombre del Usuario: ' . $this->session->get('NomApell') . '<br/>';
            echo 'Nombre completo: ' . $this->session->get('Nombre') . '<br/>';
            echo 'Estado: ' . $this->session->get('Estado') . '<br/>';
            echo 'Administrador: ' . $this->session->get('Administrador') . '<br/>';
    
            if ($this->session->has('paginas')) {
                $paginas = $this->session->get('paginas');
                if (is_array($paginas)) {
                    echo 'Páginas asociadas a la sesión:<br/>';
                    foreach ($paginas as $pagina) {
                        echo $pagina . '<br/>';
                    }
                } else {
                    echo 'Las páginas asociadas a la sesión no están en un formato válido.<br/>';
                }
            } else {
                echo 'No hay páginas asociadas a la sesión.<br/>';
            }
        } else {
            echo 'No hay datos en la sesión.';
        }
    }
    public function doLogin(){
        $validation = \Config\Services::validation();
        
        $input = $this->validate([
            'user' => [
                'rules' => 'required|min_length[5]|max_length[100]',
                'errors' => [
                    'required' => 'El campo Usuario no debe estar vacío',
                    'min_length' => 'El Usuario debe tener al menos 5 caracteres'
                ]
            ],
            'pass' => [
                'rules' => 'required|min_length[5]|max_length[20]',
                'errors' => [
                    'required' => 'El campo Clave no debe estar vacío',
                    'min_length' => 'La Clave debe tener al menos 5 caracteres',
                    'max_length' => 'La Clave no debe exceder los 20 caracteres'
                ]
            ]
        ]);
    
        if (!$input) {
            echo view('Login/Login/Login', [
                'validation' => $this->validator
            ]);
        } else {
            $request = \Config\Services::request();
            $user = $request->getPost('user');
            $pass = $request->getPost('pass');
            
            $db = \Config\Database::connect();
            $modelo = new ModeloLogin($db);
            $resultado = $modelo->login($user, $pass);
            
            if ($resultado && password_verify($pass, $resultado->v3)) {
                // Crear los datos de sesión
                $session = \Config\Services::session();
                $newdata = [
                    'id' => $resultado->v1,
                    'usuario' => $resultado->v2,
                    'Cargo' => $resultado->v4,
                    'Sucursal' => $resultado->v5,
                    'NomApell' => $resultado->v6,
                    'Nombre' => $resultado->v7,
                    'Estado' => $resultado->v8,
                    'Administrador' => $resultado->v9,
                    'logged_in' => TRUE
                ];
                $session->set($newdata);
    
                // Si el estado es 3, redirigir a la página de cambio de contraseña
                if ($resultado->v8 == 3) {
                    return redirect()->to(base_url('/Recuperacion'));
                }
                
                // Verificar si el usuario está autorizado
                if ($resultado->v8 == 0) {
                    return view('Login/Login/Login', [
                        'validation' => 'Acceso no autorizado'
                    ]);
                } else {
                    // Establecer las páginas según el rol del usuario
                    $p = array($resultado->v4);
                    $paginas = $modelo->paginas($p);
                    $newpag = [
                        'paginas' => $paginas
                    ];
                    $session->set($newpag); 
                    
                    // Redirigir según el rol del usuario
                    switch ($resultado->v4) {
                        case 1:
                            $jus = base_url('/api/Dashboard');
                            break;
                            
                        case 2: // Sucursal
                            $jus = base_url('/api/Asistencia');
                            break;
                        case 3: // Local
                            $jus = base_url('/api/Trabajador/vista');
                            break;
                        default:
                            $jus = base_url('/Login/Login/Login');
                            break;
                    }
                    
                    return redirect()->to($jus);
                }
            } else {
                return view('Login/Login/Login', [
                    'validation' => $this->validator->listErrors()
                ]);
            }
        }
    }    
    public function cerrarsesion() { 
        $this->session->destroy();
        $jus = base_url('/');
        header( 'Location: '.$jus );
        exit();
    }
    public function cambiarPassRestri() {
        $usuarioModel = new UserModelo();
        $rules = [
            'Pass_actual'     => 'required',
            'nueva_Pass'      => 'required|min_length[5]|max_length[20]',
            'confirmar_Pass'  => 'required|matches[nueva_Pass]'
        ];    
    
        if (!$this->validate($rules)) {
            $response = [
                'status' => 'error',
                'message' => $this->validator->getErrors()
            ];
        } else {
            $id = session()->get('id');
            $clave = $this->request->getPost('Pass_actual');
            $nueva_contraseña = $this->request->getPost('nueva_Pass');
            if ($usuarioModel->verificarPass($id, $clave)) {
                $usuarioModel->actualizarPassRes($id, $nueva_contraseña);
    
                // Obtener los datos actualizados del usuario
                $user = $usuarioModel->find($id);
    
                // Determinar la URL de redirección según el cargo del usuario
                switch ($user['id_cargo']) {
                    case 1:
                        $redirect_url = base_url('/api/Dashboard');
                        break;
                    case 2: // Administrador
                        $redirect_url = base_url('/api/Asistencia');
                        break;
                    case 3: // Local
                        $redirect_url = base_url('/api/Trabajador/vista');
                        break;
                    default:
                        $redirect_url = base_url('/Login/Login/Login');
                        break;
                }
    
                $response = [
                    'status' => 'success',
                    'message' => 'Contraseña actualizada correctamente.',
                    'redirect_url' => $redirect_url
                ];
            } else {
                $response = [
                    'status' => 'error',
                    'message' => 'La contraseña actual no es correcta.'
                ];
            }
        }
    
        return $this->response->setJSON($response);
    }    
}
