<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RulesOne extends Model
{
    //use HasFactory;
    protected $table ="rulesone";
    protected $fillable = [
          'id',
          'compagnie_id',
          'prix',
    ];
}
