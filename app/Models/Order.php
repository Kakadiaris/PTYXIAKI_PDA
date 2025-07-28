<?php

namespace App\Models;

use App\Models\Table;
use App\Models\OrderItem;
use App\Models\Payment;

use Illuminate\Database\Eloquent\SoftDeletes;



use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use SoftDeletes;

    public function table()
    {
        return $this->belongsTo(Table::class);
    }
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
    // App\Models\Order
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
