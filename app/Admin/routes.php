<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home');

    /// users routes
    $router->resource('users', UserController::class);

    // end users route


    ////// Customer Routes //////

    $router->resource('customers', CustomerController::class);


    ///// end Customer routes




});
