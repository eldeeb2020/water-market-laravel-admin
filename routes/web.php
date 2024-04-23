<?php

use Illuminate\Http\Request;
use Encore\Admin\Auth\Database\Role;
use Illuminate\Support\Facades\Route;
use Doctrine\DBAL\Portability\OptimizeFlags;
use Encore\Admin\Auth\Database\Administrator;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*Route::get('/', function () {
    return view('welcome');
});*/

//// user registeration routes

Route::get('/auth/register', function(){
    return view('auth.register');
});

Route::post('/auth/register',function(Request $request){
    $name = $request->name;
    $email = $request->email;
    $password = $request->password;
    $user = Administrator::where('username', $email)->first();

    if($user != null){
        die("user already exist");
    }

    $u = new Administrator();
    $u->name = $name;
    $u->username = $email;
    $u->password = password_hash($password, PASSWORD_DEFAULT);
    $u->save();

    // Assign the role "Customer" to the new user
    $customerRole = Role::where('slug', 'customer')->first();
    if ($customerRole) {
        $u->roles()->sync([$customerRole->id]);
    }

    return redirect('/auth/login');
})->name('register');
