<?php

namespace App\Admin\Controllers;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Models\Customer;
use Illuminate\Http\Request;
use Encore\Admin\Layout\Content;
use Illuminate\Support\Facades\Validator;
use Encore\Admin\Controllers\AdminController;
use Illuminate\Support\Facades\Auth;


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
