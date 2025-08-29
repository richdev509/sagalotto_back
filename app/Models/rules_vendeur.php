<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class rules_vendeur extends Model
{
    use HasFactory;
    protected $fillable = [
       'compagnie_id',
       'user_id',
       'prix',
       'prix_second',
       'prix_third',
       'prix_maryaj',
       'prix_loto3',
       'prix_loto4',
       'prix_loto5',
       'prix_gabel1',
       'prix_gabel2',
       'gabel_statut',
       'updatedated_at',

    ];
}
