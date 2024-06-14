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
        'balance',	
        'action',
        'date',
        'nombrepos',
        'montant',
        'etat',
        'created_at',
        'dateabonnement',
    ];


    public function compagnie()
    {
        return $this->belongsTo(company::class,'idcompagnie','id');
    }
}
