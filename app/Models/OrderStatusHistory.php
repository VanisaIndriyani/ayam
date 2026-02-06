<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderStatusHistory extends Model
{
    protected $fillable = [
        'order_id','status_from','status_to','note','changed_by'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'changed_by');
    }

}
