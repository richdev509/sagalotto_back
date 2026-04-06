<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class branch extends Model
{
    //this
    use HasFactory;
    
    protected $fillable = [
        'name',
        'address',
        'phone',
        'description',
        'percent_agent_only',
        'percent'
    ];
}
