<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoulGagnant extends Model
{
    //use HasFactory;
    protected $table ="boulgagnant";
    protected $fillable = [
        'tirage_id',
        'compagnie_id',
        'unchiffre',
        'secondchiffre',
        'premierchiffre',
        'troisiemechiffre',
        'etat',
        'created_',
    ];

    public function tirage_record()
    {
        return $this->belongsTo(tirage_record::class,'tirage_id','id');
    }
}
