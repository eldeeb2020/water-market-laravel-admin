<?php

namespace App\Admin\Controllers;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Models\Customer;
use Illuminate\Http\Request;
use Encore\Admin\Layout\Content;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Encore\Admin\Controllers\AdminController;


class CustomerController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Customer';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */

    



    protected function grid()
    {
        $grid = new Grid(new Customer());

        $grid->column('id', __('Id'));
        $grid->column('name', __('Name'));
        $grid->column('email', __('Email'));
        $grid->column('phone', __('phone'));
        $grid->column('password', __('Password'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Customer::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('email', __('Email'));
        $show->field('phone', __('Phone'));
        $show->field('password', __('Password'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    } 

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Customer());


        $form->textarea('name', __('Name'));
        $form->textarea('email', __('Email'));
        $form->textarea('phone', __('Phone'));
        $form->textarea('password', __('Password'));



        return $form;
    }

    /// method to get a list of all the customers
    
    public function allCustomers(Content $content)
    {
        $customers = Customer::all();
        return response()->json(['customers' => $customers], 200);
    }


    /// customer login method

    public function CustomerLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $credentials = $request->only('email', 'password');

        var_dump($credentials);

        if (!Auth::guard('customer')->attempt($credentials)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        else{

            var_dump($credentials);

            $customer = Auth::guard('customer')->user();; // Get the authenticated customer

            $token = $customer->createToken($customer->name);

            
            $token = $customer->createToken($customer->name); // used sanctum to generate token
            return response()->json([
                'message' => 'Customer logged in successfully!',
                //'customer' => $customer->toArray(), // Optionally return user details
                'token' => $token,

            ]);

        } 

        /*$email = $request->input('email');
        $password = $request->input('password');

        $credentials = $request->only('email', 'password');
        var_dump($credentials);

        $customer = Customer::where('email', $email)->first();

        if (! $customer) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        if (! Hash::check($password, $customer->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        } */
        
    }


    // Customer Register function using Api

    public function CustomerRegister(Request $request){

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:customers',
            'phone' => 'required|string',
            'password' => 'required|string|min:4|confirmed',
        ]);
    
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $customer = new Customer();
        $customer->name = $request->name;
        $customer->email = $request->email;
        $customer->phone = $request->phone;
        $customer->password = password_hash($request->password, PASSWORD_DEFAULT);
        $customer->save();

        $token = $customer->createToken($customer->name);


        return response()->json([
            'message' => 'Customer registered successfully!',
            'customer' => $customer->toArray(), // Optionally return customer data
            'token' => $token,
        ], 201); // Created status code

    } // end method


    /// customer logout method

    public function CustomerLogout(Request $request){

        //Auth::guard('customer')->logout();

        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Customer loged out successfully'
        ], 201);



    } // end method

    
}