<?php

namespace App\Http\Controllers;

use App\Models\Statistic;
use Carbon\Carbon;
use Illuminate\Http\Request;

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
        $statistics = Statistic::with('menuItem')
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('sold_count', 'desc')
            ->whereHas('menuItem')
            ->get();

        $labels = $statistics->pluck('menuItem.name');
        $sales = $statistics->pluck('sold_count');

        return view('statistics.index', compact('statistics', 'labels', 'sales', 'startDate', 'endDate'));
    }
}
