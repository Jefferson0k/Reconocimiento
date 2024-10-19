<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\SucursalModelo;
use App\Models\TrabajadorModelo;
use CodeIgniter\HTTP\ResponseInterface;

class Dashboard extends BaseController{
    
    public function vista(){
        return view('Dashboard/Plantillas/index').view('Dashboard/Plantillas/aside').view('Dashboard/Plantillas/main').view('Dashboard/Plantillas/footer');
    }
    public function index(){
        $trabajadorModel = new TrabajadorModelo();
        $sucursalModel = new SucursalModelo();
        $totalActivos = $trabajadorModel->where('estado', 1)->countAllResults();
        $totalInactivos = $trabajadorModel->where('estado', 0)->countAllResults();
        $totalTrabajadores = $trabajadorModel->countAll();
        $totalSucursales = $sucursalModel->countAll();
        return $this->response->setJSON([
            'total_activos' => $totalActivos,
            'total_inactivos' => $totalInactivos,
            'total_trabajadores' => $totalTrabajadores,
            'total_sucursales' => $totalSucursales,
        ]);
}
}
