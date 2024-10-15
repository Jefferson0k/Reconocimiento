<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->group('api', function($routes) {
    #Dashboard
    $routes->get('dashboard', 'api\Dashboard::view');
    #Reconocimiento
    $routes->get('reconocimiento', 'api\ReconcomientoControllers::view');
});
