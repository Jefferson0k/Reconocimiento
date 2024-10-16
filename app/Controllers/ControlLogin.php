<?php
namespace App\Controllers;
class ControlLogin extends BaseController {    
    public function __construct() {
        helper( [ 'form', 'url' ] );
    }
    public function index() {
        return view( 'Login/Login/Login' );
    }
} 