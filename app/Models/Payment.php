<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    // Τα πεδία που επιτρέπεται να γίνονται mass assign (όπως μέσω create())
    protected $fillable = ['order_id', 'amount', 'method', 'paid_at'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
