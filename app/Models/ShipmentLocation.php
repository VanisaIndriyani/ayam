<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShipmentLocation extends Model
{
    protected $fillable = [
        'order_id','latitude','longitude','recorded_at','note'
    ];

    protected $casts = [
        'recorded_at' => 'datetime'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
