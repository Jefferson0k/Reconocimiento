<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Dashboard extends BaseController{
    
    public function vista(){
        return view('Dashboard/Plantillas/index').view('Dashboard/Plantillas/aside').view('Dashboard/Plantillas/main').view('Dashboard/Plantillas/footer');
    }
}
