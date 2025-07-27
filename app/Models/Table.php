<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Models\Reservation;


class Table extends Model
{
    use SoftDeletes;

    //
    protected $fillable = [
        'zone_id',
        'number',
        'status',
    ];
    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function latestReservation()
    {
        return $this->hasOne(Reservation::class)->latest();
    }
}
