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
          'branch_id',
          'prix',
          'prix_second',
          'prix_third',
          'prix_maryaj',
          'prix_loto3',
          'prix_loto4',
          'prix_loto5',
          'prix_gabel1',
          'prix_gabel2',
          'gabel_status',
          

    ];
}
