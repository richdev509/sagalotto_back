<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tbladmin extends Model
{
    protected $table ="tbl_admin";
    protected $fillable = [
        'id',
        'fullname',
        'telephone',
        'adresse',
        'is_block',
        'username',
        'password',
        'role',
        
    ];

}
