<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class monitor extends Model
{
    protected $table = "monitoring";
    protected $fillable = [
        'id',
        'userid',
        'compagnieid',
        'totalexecuter',
        'totalfiche',
        'tirage_id',

    ];
}
