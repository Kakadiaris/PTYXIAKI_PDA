<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Table;
use App\Models\User;

class Reservation extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'table_id',
        'user_id',
        'guest_count',
        'reservation_at',
        'notes',
    ];

    public function table()
    {
        return $this->belongsTo(Table::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
