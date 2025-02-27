<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'amount',
        'invoice_date'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'invoice_date' => 'datetime'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
