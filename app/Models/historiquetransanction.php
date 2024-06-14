<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class historiquetransanction extends Model
{
    protected $table="historiquetransaction";
    protected $fillable = [
                        'iduser',
                        'idCompagnie',
                        'montant',
                        'libelle',
                        'idabonnement',
    ];

    public function compagnie()
    {
        return $this->belongsTo(company::class,'idCompagnie','id');
    }

    public function admin(){
        return $this->belongsTo(tbladmin::class,'iduser','id');
    }
}
