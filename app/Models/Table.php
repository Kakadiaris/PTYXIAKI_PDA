<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    //
     protected $fillable = [
        'zone',
        'number',
        'status',
    ];
    public function zone()
{
    return $this->belongsTo(Zone::class);
}

}
