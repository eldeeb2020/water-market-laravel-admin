<?php

namespace App\Models;

use App\Models\Customer;
use App\Models\OrderItems;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $guarded = [];


    // the relation between the customer and orders // one to many

    public function customer(){

        return $this->belongsTo(Customer::class);
        
    } // end method 

    public function orderitems(){
        
        return $this->hasMany(OrderItems::class);
    }





}
