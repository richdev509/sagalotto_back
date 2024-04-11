<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class limitprixachat extends Model
{
    protected $table="limit_prix_achat";
    protected $fillable = [
        'id',
        'compagnie_id',
        'bolet',
        'maryaj',
        'loto3',
        'loto4',
        'loto5',
        'boletetat',
        'maryajetat',
        'loto3etat',
        'loto4etat',
        'loto5etat',
        
        
    ];

    public function compagnie(){
        return $this->belongsTo(company::class,'compagnie_id','id');
    }

}
