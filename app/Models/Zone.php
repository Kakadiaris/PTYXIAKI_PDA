<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Zone extends Model
{
    use SoftDeletes;

    protected $fillable = ['value'];

    public function tables(): HasMany
    {
        return $this->hasMany(Table::class);
    }
}
