<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class limit_prix_achat_par_vendeur extends Model
{
     protected $table="limit_prix_achat_par_vendeurs";
    protected $fillable = [
        'id',
        'compagnie_id',
        'user_id',
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
        'included',
        'created_at',
        'updated_at'
        
        
    ];

    public function compagnie(){
        return $this->belongsTo(company::class,'compagnie_id','id');
    }
    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }

}
