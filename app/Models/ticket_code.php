<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ticket_code extends Model
{
    protected $table="ticket_code";

    protected $fillable = [
        'code',
    ];
    public function TicketVendus() {
        return $this->hasMany(TicketVendu::class, 'ticket_code_id');
    }
    
}
