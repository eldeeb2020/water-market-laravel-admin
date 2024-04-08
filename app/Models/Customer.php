<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Encore\Admin\Auth\Database\Administrator;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'email', 'phone', 'password',
    ];

    protected $hidden = [
        'password',
    ];
}
