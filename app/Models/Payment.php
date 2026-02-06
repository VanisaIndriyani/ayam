<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'order_id','provider','payment_type','transaction_id',
        'transaction_time','transaction_status','fraud_status',
        'va_number','bank','gross_amount','currency',
        'payment_url','raw_response'
    ];

    protected $casts = [
        'raw_response' => 'array',
        'transaction_time' => 'datetime'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
