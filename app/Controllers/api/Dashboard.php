<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Dashboard extends BaseController{
    
    public function view(){
        return view('Dashboard/Templeate/index').view('Dashboard/Templeate/aside').view('Dashboard/Templeate/main').view('Dashboard/Templeate/footer');
    }
}
