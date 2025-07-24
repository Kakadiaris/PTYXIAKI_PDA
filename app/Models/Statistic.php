<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Statistic extends Model
{
    use HasFactory;

    // Ορίζουμε τον πίνακα που χρησιμοποιεί το μοντέλο
    protected $table = 'statistics';

    // Ορίζουμε τα πεδία που μπορούμε να κάνουμε "mass assignable"
    protected $fillable = [
        'menu_item_id', 
        'sold_count', 
        'total_revenue', 
        'date',
    ];

    // Ορίζουμε τις σχέσεις του μοντέλου με άλλα μοντέλα
    public function menuItem()
    {
        return $this->belongsTo(MenuItem::class, 'menu_item_id');
    }
}
