<?php

namespace App\Admin\Controllers;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Models\OrderItems;
use Encore\Admin\Widgets\Box;
use Encore\Admin\Facades\Admin;
use Illuminate\Support\Facades\DB;
use Encore\Admin\Layout\Content;
use Encore\Admin\Controllers\AdminController;

class Order_itemsController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'OrderItems';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new OrderItems());

        $grid->model()->where('company_id', Admin::user()->id); // Filter items by the current company ID




        $grid->column('id', __('Id'));
        $grid->column('order_id', __('Order id'));
        $grid->column('item_id', __('Item id'));
        $grid->column('item.name', __('Item Name')); // Access the item name using the relationship
        $grid->column('company_id', __('Company id'));
        $grid->column('quantity', __('Quantity'));
        $grid->column('total_amount', __('Total amount'));
        $grid->column('status')->editable('select' , ['Pending' => 'Pending', 'Accepted' => 'Accepted', 'Out for delivery' => 'Out for delivery', 'Delivered' => 'Delivered' , 'Canceled' => 'Canceled']);
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

        $grid->disableCreateButton();
        $grid->disableActions();



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
        $show = new Show(OrderItems::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('order_id', __('Order id'));
        $show->field('item_id', __('Item id'));
        $show->field('company_id', __('Company id'));
        $show->field('quantity', __('Quantity'));
        $show->field('total_amount', __('Total amount'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        $show->panel()->tools(function ($tools) {
        $tools->disableEdit();
        $tools->disableList();
        $tools->disableDelete();
    });;

        return $show;
    }


    
    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new OrderItems());

        
        $form->text('status', __('Status'));


        $form->tools(function (Form\Tools $tools) {

            // Disable `List` btn.
            $tools->disableList();
        
            // Disable `Delete` btn.
            $tools->disableDelete();
        
            // Disable `Veiw` btn.
            $tools->disableView();
            
        });


        return $form;
    } //end method

    public function TopSellingItmes(){

        if (Admin::user()->isRole('company')){  

        $topitems = OrderItems::select('item_id', DB::raw('SUM(quantity) as total_quantity_sold'))->where('company_id', Admin::user()->id)
                            ->groupBy('item_id')
                            ->orderByDesc('total_quantity_sold')
                            ->take(5)
                            ->get();

        }

        else{

        $topitems = OrderItems::select('item_id', DB::raw('SUM(quantity) as total_quantity_sold'))
                            ->groupBy('item_id')
                            ->orderByDesc('total_quantity_sold')
                            ->take(5)
                            ->get();

        }
        
        $chartdata = [                 
        'labels' => $topitems->pluck('item.name')->toArray(),
        'data' => $topitems->pluck('total_quantity_sold')->toArray(),

        ];
        $content = new Box('Top Selling Items', view('admin.charts.top_selling_items', compact('chartdata')));


        return Admin::content(function (Content $content) use ($chartdata) {
            $content->header('Top Selling Items');
            $content->description('Top 5 sold items');
    
            // Display the chart
            $content->row(view('admin.charts.top_selling_items', compact('chartdata')));

            

        });



    } //end method 
}
