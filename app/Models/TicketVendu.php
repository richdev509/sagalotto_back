<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketVendu extends Model
{
    use HasFactory;
    protected $table ="ticket_vendu";
    protected $fillable = [
        'ticket_code_id',
        'tirage_record_id',
        'is_win',
        'winning',
        'is_calculated',
    ];

    public function ticketcode()
    {
        return $this->belongsTo(ticket_code::class,'ticket_code_id','code');
    }
    public function tirageRecord() {
        return $this->belongsTo(tirage_record::class, 'tirage_record_id');
    }
}
