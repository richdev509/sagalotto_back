<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class limitprixboul extends Model
{
    //use HasFactory;

    protected $table="limit_prix_boul";
    protected $fillable = [
        'id',
        'tirage_record',
        'compagnie_id',
        'type',
        'opsyon',
        'boul',
        'montant',
        'montant1',

        'is_general',
        'updated_at',
        'created_at',
        
        
    ];

}
