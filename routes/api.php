<?php

use Illuminate\Http\Request;


use Illuminate\Support\Facades\Route;
use App\Admin\Controllers\AuthController;
use App\Admin\Controllers\ItemController;
use App\Admin\Controllers\OrderController;
use App\Admin\Controllers\CategoryController;
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



Route::get('/auth/register', function(){
    return view('auth.register');
});

Route::get('auth/login',function(){

    return view('auth.login');
})->name('auth.login');




Route::post('customer/login',[CustomerController::class, 'CustomerLogin'])->name('customer.login');  // customer login route
Route::post('customer/register', [CustomerController::class, 'CustomerRegister'])->name('register'); // customer sign up route



// Protected routes for authenticated customers 

Route::middleware(['auth:sanctum'])->group(function(){

    Route::get('customer/profile', [CustomerController::class, 'CustomerProfile']);
    Route::get('items', [ItemController::class, 'GetAvailableItems']);
    Route::get('categories', [CategoryController::class, 'GetAllCategories']);
    Route::post('place/order', [OrderController::class, 'PlaceOrder']);
    Route::get('customer/logout', [CustomerController::class , 'CustomerLogout']);
    Route::get('customer/orders', [OrderController::class , 'CustomerOrders']);


});

/*Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('customer/me', function (Request $request) {
        $customer = $request->attributes->get('customer');
        dd($customer);
        return 'logged'; 
    });
    Route::get('items', [ItemController::class, 'GetAvailableItems']);
    Route::get('categories', [CategoryController::class, 'GetAllCategories']);
});*/

/////end customer apis


// test api

/*Route::get('/customer/home', function(){
    return view('customer.home');

}); */

//Route::get('customers', [CustomerController::class,'allCustomers']); // to get all the customers in JSON format

