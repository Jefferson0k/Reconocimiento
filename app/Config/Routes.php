<?php

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);
$routes->get('/Login', 'Login::index');
$routes->get('/Recuperacion', 'Login::Recuperacion');
$routes->post('/NuevaPassword', 'Login::cambiarPassRestri');

$routes->get('consulta-dni/(:num)', 'ConsultasDni::consultar/$1');
$routes->group('api', ['namespace' => 'App\Controllers\api'], function ($routes) {
    #Asistencias Metodos para vista y acciones
    $routes->get('Asistencia/vista', 'Asistencia::vista');
    $routes->get('Asistencia', 'Asistencia::index');
    # ruta para obtener fotos
    $routes->get('Asistencia/(:num)', 'Asistencia::show/$1');
    $routes->post('Asistencia', 'Asistencia::store');
    $routes->put('Asistencia/(:num)', 'Asistencia::update/$1');
    $routes->delete('Asistencia/(:num)', 'Asistencia::delete/$1');

    #Sucursales Metodos para la vista y acciones
    $routes->get('Sucursal/vista', 'Sucursal::Vista');
    $routes->get('Sucursal', 'Sucursal::index');
    $routes->get('Sucursal/(:num)', 'Sucursal::show/$1');
    $routes->post('Sucursal', 'Sucursal::store');
    $routes->put('Sucursal/(:num)', 'Sucursal::update/$1');
    $routes->delete('Sucursal/(:num)', 'Sucursal::delete/$1');
    $routes->post('Sucursal/Bulkload', 'Sucursal::upload_csv');
    $routes->post('UserSucursal/(:num)', 'Sucursal::getUserById/$1');
    $routes->delete('Sucursal/delete', 'Sucursal::deleteMultiple');
    $routes->get('getSucursalUsuario', 'Sucursal::getSucursalUsuario');
    $routes->get('Sucursalesv', 'Sucursal::getSucursales');

    #Horarios Metodos para la vista y acciones
    $routes->get('Horario/vista', 'Horario::vista');
    $routes->get('Horariov/(:num)', 'Horario::index/$1');
    $routes->get('Horario-Estados/(:num)', 'Horario::indexEstados/$1');
    $routes->get('Horario/(:num)/(:num)', 'Horario::show/$1/$2');
    $routes->post('Horario', 'Horario::store');
    $routes->put('Horario/(:num)/(:num)', 'Horario::update/$1/$2');
    $routes->delete('Horario/(:num)', 'Horario::delete/$1');
    $routes->post('Horario/Bulkload', 'Horario::upload_csv');
    $routes->delete('horarios/delete', 'Horario::deleteMultiple');
    $routes->get('Horariov/(:num)', 'Horario::getHorariosBySucursal/$1');

    #Trabajador metodos para la vista y acciones
    $routes->get('Trabajador/vista', 'Trabajador::vista');
    $routes->get('Trabajador/(:num)', 'Trabajador::index/$1');
    $routes->get('Trabajador-Estado/(:num)', 'Trabajador::indexEstado/$1');
    $routes->get('Sucursalesv', 'Trabajador::indexSucursal');
    $routes->get('Horariosv', 'Trabajador::indexHorarios');
    $routes->post('Trabajador', 'Trabajador::store');
    $routes->delete('Trabajador/(:num)', 'Trabajador::delete/$1');
    $routes->post('Trabajador/Bulkload', 'Trabajador::upload_csv');
    $routes->post('generate-username', 'Trabajador::generateUsernameById');
    $routes->get('Trabajador/(:num)', 'Trabajador::getTrabajadoresBySucursal/$1');
    $routes->post('Trabajador/(:num)/(:num)', 'Trabajador::update/$1/$2');
    $routes->get('Trabajador/(:num)/(:num)', 'Trabajador::show/$1/$2');
    $routes->get('trabajadores', 'Trabajador::indexGeneral');

    #Usuario metodos para la vista y acciones
    $routes->get('Usuario/vista', 'Usuario::vista');
    $routes->get('Usuario/(:num)', 'Usuario::index/$1');
    $routes->get('Usuarios/(:num)', 'Usuario::show/$1');
    $routes->post('Usuario', 'Usuario::store');
    $routes->put('Usuario/(:num)', 'Usuario::update/$1');
    $routes->delete('Usuario/(:num)', 'Usuario::delete/$1');
    $routes->get('getUserInfo', 'Usuario::getUserInfo');

    #Perfil metodos para la vista y acciones
    $routes->get('Perfil/vista', 'Perfil::vista');
    $routes->get('Perfil/(:num)', 'Perfil::show/$1');
    $routes->post('Perfil', 'Perfil::store');
    $routes->put('Perfil/(:num)', 'Perfil::update/$1');
    $routes->delete('Perfil/(:num)', 'Perfil::delete/$1');
    $routes->post('cambiarPass', 'Perfil::cambiarPass');

    #Observaciones metodo agregar Solo
    $routes->post('Observaciones', 'Observaciones::store');

    #Accesos
    $routes->get('Accesos', 'Accesos::index');
    $routes->post('Accesos', 'Accesos::store');

    #Paginas
    $routes->get('Pagina', 'Pagina::index');
    $routes->post('Pagina', 'Pagina::store');

    #Reportes
    $routes->get('Reportes/Vista', 'Reportes::vista');
    $routes->post('Reportes/(:num)', 'Reportes::index/$1');
    $routes->post('Reportes-General/(:num)', 'Reportes::indexGeneral/$1');
    $routes->get('Reportes-Incio/(:num)', 'Reportes::getTrabajadoresBySucursal/$1');

    #Permisos
    $routes->get('Permisos/Vista', 'Permisos::vista');

    #Cargo
    $routes->get('Cargo/vista','Cargo::vista');
    $routes->get('Cargo','Cargo::index');
    $routes->get('Cargo-vista','Cargo::indexVista');
    $routes->post('Cargo','Cargo::store');
    $routes->get('Cargo/(:num)', 'Cargo::show/$1');
    $routes->put('Cargo/(:num)', 'Cargo::update/$1');
    $routes->delete('Cargo/(:num)', 'Cargo::delete/$1');

    #Dashboard
    $routes->get('Dashboard','Dashboard::vista');
    $routes->get('Dashboard/General','Dashboard::index');
    #Turnos
    $routes->get('Trunos/vista','Trunos::vista');
    $routes->get('Trunos/(:num)','Trunos::index/$1');
    $routes->get('Trunos/(:num)/(:num)','Trunos::show/$1/$2');
    $routes->put('Trunos/(:num)/(:num)', 'Trunos::update/$1/$2');
    $routes->get('Trunos-Estados/(:num)', 'Trunos::indexE/$1');
    $routes->post('Trunos','Trunos::store');
    $routes->delete('Trunos/(:num)', 'Trunos::delete/$1');
    $routes->post('Trunos/Bulkload', 'Trunos::upload_csv');
    $routes->delete('Trunos/delete', 'Trunos::deleteMultiple');

});