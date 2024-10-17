<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class ConsultasDni extends Controller{
    public function consultar($dni = null){
        if (empty($dni)) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Debe proporcionar un DNI válido']);
        }
        $token = '7230|tT5f2bolJT4ilexqLUvDiILpvNdZCS2RCZy8d3nS';
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://apis.aqpfact.pe/api/dni/' . $dni,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 2,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Referer: https://apis.net.pe/consulta-dni-api',
                'Authorization: Bearer ' . $token
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $persona = json_decode($response);
        if (!$persona) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'No se encontraron datos para el DNI proporcionado']);
        }

        return $this->response->setJSON($persona);
    }
}
