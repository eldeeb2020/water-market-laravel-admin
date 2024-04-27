<?php

namespace App\Admin\Controllers;

use App\Models\Item;
use App\Models\Order;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Models\OrderItems;
use Illuminate\Http\Request;
use Encore\Admin\Facades\Admin;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Encore\Admin\Controllers\AdminController;

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

        // Eager load orderitems relationship for efficient retrieval
        $grid->model()->with('orderitems');

        // Filter by current company's ID through the orderitems relationship
        $grid->model()->whereHas('orderitems', function ($query) {
            $query->where('company_id', Admin::user()->id);
        });

        $grid->column('id', __('Id'));

        $grid->column('orderitems.company_id', __('Company Id'));

        $grid->column('customer_id', __('Customer id'));
        $grid->column('order_number', __('Order number'));
        $grid->column('order_date', __('Order date'));
        $grid->column('total_amount', __('Total amount'));
        $grid->column('status', __('Status'));
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
    } //end method

    public function CustomerOrders(){

        $orders = Auth::user()->order()->with('customer')->get();
        if ($orders->isEmpty()){
            return response()->json(['message' => 'you have no order'],200);
        }else {
            return response()->json($orders , 200);
        }
        
    } // end method

    public function PlaceOrder(Request $request){

        $validator = Validator::make($request->all(), [
            'order_items' => 'required|array',
            'order_items.*.item_id' => 'required|integer|exists:items,id',
            'order_items.*.quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()){
            return response()->json(['message' => $validator->errors()->first()] , 422);
        }

        try{
            DB::transaction(function () use ($request) {  

                $order = new Order();        /// orders table
                $order->customer_id = Auth::id();
                $order->total_amount = $this->CalculateTotalOrderAmount($request->order_items);
                $order->save();

                foreach($request->order_items as $order_item){

                    $item_id = $order_item['item_id'];
                    $quantity = $order_item['quantity'];

                    $item = Item::where('id', $item_id)->first();

                    if ($item) {

                        $items = new OrderItems();
                        $items->order_id = $order->id;
                        $items->item_id = $item_id;
                        $items->company_id = $item->company_id; // to get company_id from item tables
                        $items->quantity = $quantity;
                        $items->total_amount = $quantity * $item->price;
                        $items->save();

                        $item->quantity -= $quantity;
                        $item->save();
                    } 
                }

                $order->save(); // Save order again with the final total amount

        });

        return response()->json('order placed successfully' , 200);
        }
        catch(Exception $e){
            return response()->json($e);
        }


    
    } // end method

    public function CalculateTotalOrderAmount($order_items)
    {
        $Total_Amount = 0;
        foreach ($order_items as $order_item) {
            $item_id = $order_item['item_id'];
            $quantity = $order_item['quantity'];

            $item = Item::where('id' , $item_id)->first();

            if($item){
                $Total_Amount += $quantity * $item->price;
            }
            
        }
        return $Total_Amount;
    } // end method
}
