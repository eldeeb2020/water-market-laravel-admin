<?php

namespace App\Admin\Controllers;

use App\Models\Order;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class OrderController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Order';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Order());

        $grid->column('id', __('Id'));
        $grid->column('company_id', __('Company id'));
        $grid->column('order_number', __('Order number'));
        $grid->column('order_date', __('Order date'));
        $grid->column('total_amount', __('Total amount'));
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
        $show = new Show(Order::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('company_id', __('Company id'));
        $show->field('order_number', __('Order number'));
        $show->field('order_date', __('Order date'));
        $show->field('total_amount', __('Total amount'));
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
        $form = new Form(new Order());

        $form->number('company_id', __('Company id'));
        $form->text('order_number', __('Order number'));
        $form->datetime('order_date', __('Order date'))->default(date('Y-m-d H:i:s'));
        $form->decimal('total_amount', __('Total amount'));

        return $form;
    }
}
