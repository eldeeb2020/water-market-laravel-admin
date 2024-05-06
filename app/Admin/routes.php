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

    /// users routes  /////
    
    $router->resource('users', UserController::class);

    // end users route *****


    ////// Customer Routes //////

    $router->resource('customers', CustomerController::class);

    ///// end Customer routes ******



    ///// Company Routes /////

    $router->resource('companies', CompanyController::class);

    ///// End Company Routes ********


    ///// Item Routes ///////

    $router->resource('items', ItemController::class);

    ///// End Item Routes ******


    ///// Order Routes ////////

    $router->resource('orders', OrderController::class);

    ///// End Order routes *******


     ///// Category Routes  ///////

   $router->resource('categories', CategoryController::class);

   ////  End Category Routes *******


   ///// order-items routes /////////

   $router->resource('order-items', Order_itemsController::class);

   ///// end order-items routes *******

});


  




