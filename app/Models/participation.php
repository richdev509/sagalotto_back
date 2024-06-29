<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class participation extends Model
{
    protected $table = "particiapation_tirage";
    protected $fillable = [
    'id',
    'compagnie_id',
    'tirage_id',
    'date_',
    'infos',
    'updated_at',
    'created_at',
    ];	
}
