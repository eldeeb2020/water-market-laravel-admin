<?php

namespace App\Admin\Controllers;

use App\Models\Item;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Models\Company;
use App\Models\Category;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Auth\Database\Administrator;
use Encore\Admin\Controllers\AdminController;

class ItemController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Item';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Item());

        $grid->model()->where('company_id', Admin::user()->id); // Filter items by the current company ID


        $grid->column('id', __('Id'));
        $grid->column('name', __('Name'));
        $grid->column('description', __('Description'));
        $grid->column('category_id', __('Category id'));
        $grid->column('company_id', __('Company id'));
        $grid->column('price', __('Item price'));
        $grid->column('photo', __('Item photo'));
        $grid->column('quantity', __('Item Quantity'));
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
        $show = new Show(Item::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('description', __('Description'));
        $show->field('category_id', __('Category id'));
        $show->field('company_id', __('Company id'));
        $show->filed('price', __('Item price'));
        $show->field('photo', __('Item photo'));
        $show->field('quantity', __('Item Quantity'));
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
        $form = new Form(new Item());

        $form->text('name', __('Name'))->rules('required');
        $form->textarea('description', __('Description'));
        
        $categories = Category::all()->pluck('name','id');
        $form->select('category_id','Select Category')->options($categories)->rules('required');

        /// Another Solution

        /*$categories = [];
        foreach (Category::all() as $key => $c){
            $categories[$c->id] = $c->name; 
        }
        $form->select('category_id','Select Category')->options($categories); */

        //$form->number('category_id', __('Category id'));
        //$companies = Company::all()->pluck('name','id');
        //$form->select('company_id','Select Company')->options($companies)->rules('required');

        $user = Admin::user();
        $userName = $user->username;
        $userId = $user->id;
        $form->select('company_id', 'Select Company')->options([$userId => $userName])->rules('required');


        $form->number('price', __('Price'));
        $form->image('photo', __('Photo'));
        $form->number('quantity', __('Quantity'));


        $form->confirm('Confirm Edit', 'edit');
        $form->confirm('Confirm Creation', 'create');



        return $form;
    }
}
