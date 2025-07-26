<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

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
}
