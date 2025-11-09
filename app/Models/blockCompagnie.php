<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class blockCompagnie extends Model
{
    use HasFactory;

    protected $fillable = [
        'compagnie_id',
        'message',
        'blocked_at',
        'action'
    ];
}
