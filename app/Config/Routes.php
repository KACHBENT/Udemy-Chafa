<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// =====================================================
// TODO ADMINISTRACION DE ACCESOS POR RUTAS 
// =====================================================
/*
$routes->group('auth', ['filter' => 'csrf'], static function ($routes): void {
    $routes->get(
        'login',
        'AdministracionAcceso::showLoginForm',
        ['as' => 'auth.login']
    );
    $routes->post(
        'login',
        'AdministracionAcceso::login'
    );

    $routes->get(
        'register',
        'AdministracionAcceso::showRegisterForm',
        ['as' => 'auth.register']
    );
    $routes->post(
        'register',
        'AdministracionAcceso::register'
    );

    $routes->get(
        'verificar-email/(:any)',
        'AdministracionAcceso::verificarEmail/$1'
    );
    $routes->get(
        'verificado',
        'AdministracionAcceso::verificado'
    );

    $routes->post(
        'refresh',
        'AdministracionAcceso::refresh'
    );
    $routes->get(
        'logout',
        'AdministracionAcceso::logout'
    );
});
*/

$routes->get(
    'Home',
    'HomeController::index'
);
