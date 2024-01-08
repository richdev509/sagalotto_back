<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tirage_record extends Model
{
    protected $table ="tirage_record";
    protected $fillable = [
        'id',
        'compagnie_id',
        'tirage_id',
        'name',
        'hour',
        'is_active',
    ];


    public function tirage()
    {
        return $this->belongsTo(tirage::class,'tirage_id','id');
    }
}
