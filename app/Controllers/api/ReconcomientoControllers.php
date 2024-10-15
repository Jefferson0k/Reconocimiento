<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class ReconcomientoControllers extends BaseController{

    public function view(){
        return view('Dashboard/Modulos/Reconocimiento');
    }
}
