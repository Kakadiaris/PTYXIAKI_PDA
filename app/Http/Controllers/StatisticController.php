<?php

namespace App\Http\Controllers;

use App\Models\Statistic;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatisticController extends Controller
{
    /**
     * Προβολή στατιστικών για την τρέχουσα εβδομάδα
     */
    public function showCurrentWeekStatistics(Request $request)
    {
        $startDate = now()->startOfWeek();
        $endDate = now()->endOfWeek();

        return $this->renderStatistics($startDate, $endDate);
    }

    /**
     * Προβολή στατιστικών για τον τρέχοντα μήνα
     */
    public function showCurrentMonthStatistics(Request $request)
    {
        $startDate = now()->startOfMonth();
        $endDate = now()->endOfMonth();

        return $this->renderStatistics($startDate, $endDate);
    }

    /**
     * Προβολή στατιστικών για τον προηγούμενο μήνα
     */
    public function showPreviousMonthStatistics(Request $request)
    {
        $startDate = now()->subMonth()->startOfMonth();
        $endDate = now()->subMonth()->endOfMonth();

        return $this->renderStatistics($startDate, $endDate);
    }

    /**
     * Προβολή στατιστικών για συγκεκριμένη ημερομηνία
     */
    public function showStatisticsByDate(Request $request)
    {
        $date = $request->input('date')
            ? Carbon::parse($request->input('date'))->startOfDay()
            : Carbon::today();

        return $this->renderStatistics($date, $date);
    }

    /**
     * Προβολή στατιστικών για custom χρονικό διάστημα
     */
    public function showStatisticsByPeriod(Request $request)
    {
        $startDate = $request->filled('start_date')
            ? Carbon::parse($request->input('start_date'))->startOfDay()
            : now()->startOfMonth();

        $endDate = $request->filled('end_date')
            ? Carbon::parse($request->input('end_date'))->endOfDay()
            : now()->endOfMonth();

        return $this->renderStatistics($startDate, $endDate);
    }

    /**
     * Κοινή μέθοδος που φέρνει τα στατιστικά και επιστρέφει το view
     */
    private function renderStatistics(Carbon $startDate, Carbon $endDate)
    {
        $stats = Statistic::query()
            ->select([
                'menu_item_id',
                DB::raw('SUM(sold_count) AS total_sold'),
                DB::raw('SUM(total_revenue) AS total_revenue'),
            ])
            ->whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])
            ->groupBy('menu_item_id')
            ->with('menuItem')
            ->orderByDesc('total_sold')
            ->get();

        $labels = $stats->map(fn($s) => $s->menuItem->name);
        $sales  = $stats->pluck('total_sold');

        return view('statistics.index', [
            'statistics' => $stats,
            'labels'     => $labels,
            'sales'      => $sales,
            'startDate'  => $startDate,
            'endDate'    => $endDate,
        ]);
    }
}
