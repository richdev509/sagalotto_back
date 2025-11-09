<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class facture extends Model
{
    protected $table="factures";
    protected $fillable = [
        'id',
        'compagnie_id',
        'amount',
        'plan',
        'number_pos',
        'paid_amount',
        'due_date',
        'created_at',
        'updated_at',
        'month_added',
        'is_paid',
        'paid_at',
        'payment_method',
        'payment_id',
        'currency',
        'description',
        'facture_image'
    ];
}
