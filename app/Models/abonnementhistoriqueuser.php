<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class abonnementhistoriqueuser extends Model
{
    protected $table ="abonnementhistorique";
    protected $fillable = [
        'id',
        'iduser',
        'idcompagnie',
        'nombremois',	
        'action',
        'date',
    ];
}
