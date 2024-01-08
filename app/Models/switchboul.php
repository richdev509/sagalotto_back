<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class switchboul extends Model
{
    protected $table ="switchboul";
    protected $fillable = [
        'id',
        'id_compagnie',
        'boul',
    ];

}
