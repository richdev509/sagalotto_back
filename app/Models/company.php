<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class company extends Model
{
    protected $table="companies";
    protected $fillable = [
            'id',
            'name',
            'code',
            'address',
            'city',
            'phone',
            'logo',
            'email',
            'plan',
            'info',
            'password',
            'dateplan',
            'dateexpiration',
    ];
}
