<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class maryajgratis extends Model
{
    protected $table="maryaj_gratis";
    protected $fillable = [
        'id',
        'prix',
        'branch_id',
        'etat',
        'compagnie_id',
        
    ];


    public function compagnie(){
        return $this->belongsTo(company::class,'compagnie_id','id');
    }
}
