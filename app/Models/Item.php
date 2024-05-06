<?php

namespace App\Models;

use App\Models\OrderItems;
use Illuminate\Database\Eloquent\Model;
use Encore\Admin\Auth\Database\Administrator;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends Model
{
    use HasFactory;

    protected $guarded = [];


    /// the relation between the item and the category // one to many

    public function category(){

        return $this->belongsTo(Category::class);
    
    }  // end method


    /// relationship with company model

    public function company(){

        return $this->belongsTo(Administrator::class);
    } // end method


    
    public function orderitems(){
        
        return $this->hasMany(OrderItems::class);
    }


}
