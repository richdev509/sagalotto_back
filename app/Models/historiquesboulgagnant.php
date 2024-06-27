<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class historiquesboulgagnant extends Model
{
    protected $table ="historiqueboulgagnant";
    protected $fillable = [
        'tirage_id',
        'unchiffre',
        'secondchiffre',
        'premierchiffre',
        'troisiemechiffre',
        'etat',
        'created_',
    ];

    public function tirage()
    {
        return $this->belongsTo(tirage::class,'tirage_id','id');
    }
}
