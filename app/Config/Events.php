<?php

namespace Config;

use CodeIgniter\Events\Events;
use CodeIgniter\Exceptions\FrameworkException;
use CodeIgniter\HotReloader\HotReloader;

/*
 * --------------------------------------------------------------------
 * Application Events
 * --------------------------------------------------------------------
 * Events allow you to tap into the execution of the program without
 * modifying or extending core files. This file provides a central
 * location to define your events, though they can always be added
 * at run-time, also, if needed.
 *
 * You create code that can execute by subscribing to events with
 * the 'on()' method. This accepts any form of callable, including
 * Closures, that will be executed when the event is triggered.
 *
 * Example:
 *      Events::on('create', [$myInstance, 'myMethod']);
 */

Events::on('pre_system', static function (): void {
    if (ENVIRONMENT !== 'testing') {
        if (ini_get('zlib.output_compression')) {
            throw FrameworkException::forEnabledZlibOutputCompression();
        }

        while (ob_get_level() > 0) {
            ob_end_flush();
        }

        ob_start(static fn ($buffer) => $buffer);
    }

    /*
     * --------------------------------------------------------------------
     * Debug Toolbar Listeners.
     * --------------------------------------------------------------------
     * If you delete, they will no longer be collected.
     */
    if (CI_DEBUG && ! is_cli()) {
        Events::on('DBQuery', 'CodeIgniter\Debug\Toolbar\Collectors\Database::collect');
        Services::toolbar()->respond();
        // Hot Reload route - for framework use on the hot reloader.
        if (ENVIRONMENT === 'development') {
            Services::routes()->get('__hot-reload', static function (): void {
                (new HotReloader())->run();
            });
        }
    }
});
/*Events::on('post_controller_constructor', function(){
    $router = service('router');
    $class = strtoupper($router->controllerName());
    $method = strtoupper($router->methodName());
    $session = \Config\Services::session(); 
    $nocontrolados = array('\APP\CONTROLLERS\CONTROLLOGIN', '\APP\CONTROLLERS\LOGIN', '\APP\CONTROLLERS\ERRORACCESO');

    // Verificar si la clase actual está en la lista de no controlados
    if (!in_array($class, $nocontrolados)){
        if(!$session->has('usuario')){
            // Redirigir al login si no hay usuario en la sesión
            $jus = base_url('/Login/index');
            header('Location: '.$jus);
            exit();
        } else {
            // Obtener las páginas permitidas desde la sesión
            $paginas = $session->get('paginas');  

            // Asegurarse de que $paginas sea un array
            if (!is_array($paginas)) {
                $paginas = array(); // Inicializar como array vacío si no lo es
            }

            // Verificar si la combinación de clase y método está en las páginas permitidas
            if (!in_array($class.$method, $paginas)) {    
                // Redirigir a una página de error de acceso si no está permitido
                $jus = base_url('/ErrorAcceso/index');
                header('Location: '.$jus);
                exit(); 
            }
        }
    }
});*/