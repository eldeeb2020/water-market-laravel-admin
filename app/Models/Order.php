<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $guarded = [];


    // the relation between the customer and orders // one to many

    public function customer(){

        return $this->belongTo(Customer::class);
        
    } // end method

}
