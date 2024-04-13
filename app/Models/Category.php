<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $guarded = [];  


    /// the relation between the item and the category // one to many

    public function item(){

        return $this->hasMany(Item::class);
    } // end method
} 
