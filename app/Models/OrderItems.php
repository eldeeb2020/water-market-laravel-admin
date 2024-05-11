<?php

namespace App\Models;

use App\Models\Item;
use App\Models\Order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderItems extends Model
{
    use HasFactory;

    protected $guarded = [];

    const STATUSES = ['Pending' => 'Pending', 'Delivered' => 'Delivered', 'Out for delivery' => 'Out for delivery', 'Canceled' => 'Canceled', 'Accepted' => 'Accepted'];




    public function order()
    {
        return $this->belongsTo(Order::class);
    } // end method

    public function item()
    {
        return $this->belongsTo(Item::class);
        
    } //end method
}
