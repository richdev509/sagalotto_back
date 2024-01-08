<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tirage extends Model
{
    protected $table ="tirage";
    protected $fillable = [
        'id',
        'name',
        
    ];

    public function tirageRecord()
    {
        return $this->hasMany(tirage_record::class,'tirage_id','id');
    }
}
