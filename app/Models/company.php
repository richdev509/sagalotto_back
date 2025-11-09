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
        'fullname',
            'code',
            'address',
            'city',
            'phone',
        'whatsapp',
            'logo',
            'email',
            'plan',
            'info',
            'is_block',
        'proprietaire',
            'password',
            'dateplan',
            'dateexpiration',
    ];
}
