<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

        return $this->belongsTo(company::class);
    } // end method


}
