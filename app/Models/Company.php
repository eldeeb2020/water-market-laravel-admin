<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email',];


    // Relationship with User model

    public function user(){
        
        return $this->hasOne(User::Class); // one to one relationship

    } // end method

    // relationship with item model

    public function item(){

        return $this->hasMany(Item::class);
        
    } // end method






}
