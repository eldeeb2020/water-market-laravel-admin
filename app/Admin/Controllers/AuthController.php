<?php

namespace App\Admin\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Encore\Admin\Controllers\AuthController as BaseAuthController;

class AuthController extends BaseAuthController
{

    /*protected $guard = 'customer';
    protected $loginView = 'admin.auth.login';
    protected $redirectTo = '/admin/dashboard';
    */


    /*public function CustomerLogin(Request $request)
        {


            $credentials = $request->only('email', 'password');





            if (Auth::guard('customer')->attempt($credentials)) {
                $user = Auth::guard('customer')->user();
                $token = $user->createToken('Customer Token')->accessToken;

                return response()->json(['token' => $token]);
            }



            return response()->json(['error' => 'Unauthorized'], 401,);
            
        } */


public function CustomerLogin(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        // Attempt to authenticate the user
        if (Auth::guard('customer')->attempt(['email' => $request->email, 'password' => $request->password])) {
            // Authentication successful
            $user = Auth::guard('customer')->user();
            $token = $user->createToken('AuthToken')->accessToken;
            return response()->json(['token' => $token], 200);
        } else {
            // Authentication failed
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

}
