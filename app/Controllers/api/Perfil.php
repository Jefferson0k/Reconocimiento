<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\UserModelo;
use CodeIgniter\HTTP\Request;
use CodeIgniter\HTTP\ResponseInterface;

class Perfil extends BaseController{
    public function vista(){
        return view('Dashboard/Plantillas/index').view('Dashboard/Plantillas/aside').view('Dashboard/Modulos/Perfil/Perfil').view('Dashboard/Plantillas/footer');
    }
    public function cambiarPass() {
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
                $usuarioModel->actualizarPass($id, $nueva_contraseña);
                $response = [
                    'status' => 'success',
                    'message' => 'Contraseña actualizada correctamente.'
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
