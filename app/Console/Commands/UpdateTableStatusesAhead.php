<?php

namespace App\Console\Commands;

use App\Models\Table;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateTableStatusesAhead extends Command
{
    protected $signature = 'tables:update-statuses-ahead {hours=4}';
    protected $description = 'Κανουμε τα τραπεζια reserved αν υπάρχει κράτηση σε 4 ώρες.';

    public function handle(): int
    {
        $hours = (int) $this->argument('hours');
        $now   = now();
        $to    = now()->addHours($hours);

        // Ποια τραπέζια έχουν κράτηση μέσα στις επόμενες N ώρες;
        $upcomingIds = Reservation::whereBetween('reservation_at', [$now, $to])
            ->pluck('table_id')->unique()->all();

        // Κάνε reserved όσα έχουν επερχόμενη κράτηση (αν δεν είναι occupied)
        Table::whereIn('id', $upcomingIds)
            ->where('status', '!=', 'pending','paid')
            ->update(['status' => 'reserved']);

        $this->info("Έχουν γίνει update όσα τραπέζια πρέπει για τις επόμενες {$hours} ώρες.");
        return self::SUCCESS;
    }
}
