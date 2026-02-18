<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->group('api/v1', ['filter' => 'apiAuth'], function ($routes) {
    $routes->group('queue', function($routes) {
        $routes->post('send', 'Api\v1\QueueController::send');
        $routes->get('status/(:num)', 'Api\v1\QueueController::status/$1');
    });

    $routes->group('bridge', function($routes) {
        $routes->post('inject', 'Api\v1\BridgeController::inject');
        $routes->post('clear', 'Api\v1\BridgeController::clear');
    });
});

$routes->get('admin/login', 'Admin\Auth::login');
$routes->post('admin/login', 'Admin\Auth::attemptLogin');
$routes->get('admin/logout', 'Admin\Auth::logout');

$routes->group('admin', ['filter' => 'adminAuth'], function ($routes) {
    $routes->get('/', 'Admin\Dashboard::index');
    
    // SMTP Management
    $routes->get('smtp', 'Admin\Smtp::index');
    $routes->get('smtp/new', 'Admin\Smtp::new');
    $routes->post('smtp/create', 'Admin\Smtp::create');
    $routes->get('smtp/edit/(:num)', 'Admin\Smtp::edit/$1');
    $routes->post('smtp/update/(:num)', 'Admin\Smtp::update/$1');
    $routes->get('smtp/delete/(:num)', 'Admin\Smtp::delete/$1');
    $routes->post('smtp/toggle/(:num)', 'Admin\Smtp::toggleStatus/$1');
    $routes->post('smtp/test', 'Admin\Smtp::test');
    $routes->get('smtp/authorize/(:num)', 'Admin\Smtp::authorize/$1');
    $routes->get('smtp/oauth-callback', 'Admin\SmtpOAuth::callback');

    // Queue Management
    $routes->get('queue', 'Admin\Queue::index');
    $routes->get('queue/new', 'Admin\Queue::create');
    $routes->post('queue/store', 'Admin\Queue::store');
    $routes->get('queue/view/(:num)', 'Admin\Queue::view/$1');
    $routes->get('queue/retry/(:num)', 'Admin\Queue::retry/$1');
    $routes->get('queue/delete/(:num)', 'Admin\Queue::delete/$1');

    // Logs
    $routes->get('logs', 'Admin\Logs::index');

    // Settings
    $routes->get('settings', 'Admin\Settings::index');
    $routes->post('settings/save', 'Admin\Settings::save');

    // Users
    $routes->get('users', 'Admin\Users::index');
    $routes->post('users/save', 'Admin\Users::save');
    $routes->get('users/generateKey/(:num)', 'Admin\Users::generateKey/$1');
    $routes->get('users/delete/(:num)', 'Admin\Users::delete/$1');
});
