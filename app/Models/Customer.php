<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Encore\Admin\Auth\Database\Administrator;
use Illuminate\Contracts\Auth\Authenticatable;


class Customer extends Model implements Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'name', 'email', 'phone', 'password',
    ];

    protected $hidden = [
        'password',
    ];


    // the relation between the customer and orders // one to many

    public function order(){

        return $this->hasMany(Order::class);
    } // end method





    // Implementing the required methods of the Authenticatable interface
    public function getAuthIdentifierName()
    {
        return 'id'; // Change 'id' to the name of the identifier column in your customers table
    }

    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function getRememberToken()
    {
        return $this->remember_token;
    }

    public function setRememberToken($value)
    {
        $this->remember_token = $value;
    }

    public function getRememberTokenName()
    {
        return 'remember_token';
    }
}
