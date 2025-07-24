<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\OrderItem;
use App\Models\Statistic;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UpdateStatistics extends Command
{
    protected $signature = 'statistics:update';
    protected $description = 'Update the statistics table with sales data for menu items';

    public function handle()
    {
        // Ανακτούμε τα order items και υπολογίζουμε τις πωλήσεις και τον τζίρο
        $orderItems = OrderItem::with('menuItem')
            ->select(DB::raw('menu_item_id, COUNT(*) as sold_count, SUM(price) as total_revenue, DATE(created_at) as date'))
            ->groupBy('menu_item_id', DB::raw('DATE(created_at)')) // Ομαδοποίηση ανά ημερομηνία
            ->get();

        foreach ($orderItems as $item) {
            // Ενημέρωση ή δημιουργία νέας εγγραφής στον πίνακα statistics
            Statistic::updateOrCreate(
                ['menu_item_id' => $item->menu_item_id, 'date' => $item->date], // Αν υπάρχει, ενημερώνουμε
                ['sold_count' => $item->sold_count, 'total_revenue' => $item->total_revenue]
            );
        }

        $this->info('Statistics updated successfully!');
    }
}
