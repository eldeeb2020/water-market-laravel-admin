<?php

use App\Admin\Controllers\AuthController;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
//use Admin\Controllers\CustomerController;
use App\Admin\Controllers\CustomerController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


//// test route

Route::get('test', function () {
    return 'hello world';
});

///end test route


/// user apis

Route::middleware('auth:api')->get('test-customer', function (Request $request) {
    return $request->user();
});

//// end user apis


////// customers apis

Route::get('customers', [CustomerController::class,'allCustomers']); // to get all the customers in JSON format

Route::post('/customer/login',[AuthController::class, 'CustomerLogin'])->name('customer.login');  // customer login route


/////end customer apis


