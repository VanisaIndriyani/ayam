<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id','cart_id','code','status','payment_status',
        'total_amount','shipping_cost','grand_total',
        'shipping_name','shipping_phone','shipping_address',
        'shipping_city','shipping_postal_code',
        'courier','service','weight',
        'tracking_number','tracking_url',
        'latitude','longitude',
        'ordered_at','paid_at','shipped_at','completed_at'
    ];

    protected $casts = [
        'ordered_at'   => 'datetime',
        'paid_at'      => 'datetime',
        'shipped_at'   => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function statusHistories()
    {
        return $this->hasMany(OrderStatusHistory::class);
    }

    public function shipmentLocations()
    {
        return $this->hasMany(ShipmentLocation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
