<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

$routes->group('', ['filter' => 'auth'], static function ($routes) {
    $routes->get(
        '/',
        'HomeController::index'
    );
});

$routes->group('perfil', ['filter' => 'auth'], static function ($routes) {
    $routes->get(
        '/',
        'PerfilController::index'
    );
    $routes->post(
        'actualizar',
        'PerfilController::actualizar'
    );
});


$routes->group('usuarios', ['filter' => 'auth'], static function ($routes) {
    $routes->get(
        '/',
        'UsuarioCrudController::index',
        ['filter' => 'role:Administrador']
    );
    $routes->get(
        'nuevo',
        'UsuarioCrudController::nuevo',
        ['filter' => 'role:Administrador']
    );
    $routes->post(
        'guardar',
        'UsuarioCrudController::guardar',
        ['filter' => 'role:Administrador']
    );
    $routes->get(
        'editar/(:num)',
        'UsuarioCrudController::editar/$1',
        ['filter' => 'role:Administrador']
    );
    $routes->post(
        'actualizar/(:num)',
        'UsuarioCrudController::actualizar/$1',
        ['filter' => 'role:Administrador']
    );
    $routes->get(
        'eliminar/(:num)',
        'UsuarioCrudController::eliminar/$1',
        ['filter' => 'role:Administrador']
    );
});


// =====================================================
// ! ADMINISTRACION DE ACCESOS POR RUTAS 
// =====================================================
/* todo Ejemplo de filtro por rol
$routes->get(
        '/',
        'DetalleProductoController::index',
        ['filter' => 'role:ADMIN,EMPLEADO']
    );

*/

$routes->group('acceso', static function ($routes) {
    $routes->get(
        'login',
        'AccesoController::login'
    );
    $routes->post(
        'autenticar',
        'AccesoController::autenticar'
    );
    $routes->get(
        'registro',
        'AccesoController::registro'
    );
    $routes->post(
        'guardar-registro',
        'AccesoController::guardarRegistro'
    );
    $routes->get(
        'recuperar',
        'AccesoController::recuperar'
    );
    $routes->post(
        'enviar-recuperacion',
        'AccesoController::enviarRecuperacion'
    );
    $routes->get(
        'restablecer',
        'AccesoController::restablecer'
    );
    $routes->post(
        'actualizar-password',
        'AccesoController::actualizarPassword'
    );
    $routes->get(
        'logout',
        'AccesoController::logout'
    );
});



// =====================================================
// ! ruta de bienvenida
// =====================================================
