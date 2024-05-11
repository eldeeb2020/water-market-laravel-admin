<?php

namespace App\Admin\Controllers;

use Exception;
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
Use Encore\Admin\Widgets\Table;


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
        $grid->column('customer_id', __('Customer id'));
        $grid->column('total_amount', __('Total amount'));
        $grid->column('orderitems', __('Number of Order Items'))->display(function ($orderitems) {
            $count = count($orderitems);
            return $count;
        })->expand(function ($model) {

            $orderitems = $model->orderitems()->take(20)->get()->map(function ($orderitem) {
                return $orderitem->only(['item_id', 'company_id', 'total_amount' , 'status']);
            });
            return new Table(['Item_id', 'Company_id', 'Total_amount' , 'Status'], $orderitems->toArray());
        });

        $grid->column('test', __('Invoice'))->modal('Order Invoice', function ($model) {

            $customer = $model->customer;
            $orderitem = $model->orderitems;
            $orderitems = $model->orderitems()->with('item.company')->get();

            $customerdata = [
               
                [ $customer->name,
                 $customer->phone],
            ];
        
            // Initialize an empty array to hold the table data for order items
            $orderitemdata = [
                
            ];
        
            // Loop through each order item to build the table rows
            foreach ($orderitems as $orderitem) {
                $orderitemdata[] = [
                    $orderitem->item->name,
                    $orderitem->status,
                    $orderitem->item->company->name,
                ];
            }
        

            $customertable = new Table(['Customer Name', 'Customer Phone'], $customerdata);
            $orderitemtable = new Table(['Item Name', 'Item Status', 'Company Name'], $orderitemdata);

            return '<div style="text-align: center; font-size: 24px;">Customer Details</div><br>' . $customertable->render() . '<br><div style="text-align: center; font-size: 24px;">Order Details</div><br>' . $orderitemtable->render();
        });
        $grid->column('order_date', __('Order date'));
       
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
                $insufficientitems = $this->CheckItemQuantities($request->order_items);
                if (count($insufficientitems) > 0){

                    throw new Exception("Insufficient quantity for items: " . implode(', ', $insufficientitems));
                }
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

                $order->save(); //  to save the order again with the final total amount

        });

        return response()->json('order placed successfully' , 200);
        }
        catch(Exception $e){
            return response()->json($e->getMessage(), 500);
        }


    
    } // end method

    private function CheckItemQuantities($order_items)
    {
        $insufficient_items = [];
        foreach ($order_items as $orderitem) {
            $item_id = $orderitem['item_id'];
            $quantity = $orderitem['quantity'];

            $item = Item::where('id', $item_id)->first();
            if ($item && $item->quantity < $quantity) {
                $insufficient_items[] = $item_id;
            }
        }
        return $insufficient_items;
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
