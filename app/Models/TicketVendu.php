<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketVendu extends Model
{
    use HasFactory;
    protected $table ="ticket_vendu";
    protected $fillable = [
        'tirage_record_id',
        'is_win',
        'winning',
        'is_calculated',
    ];
}
