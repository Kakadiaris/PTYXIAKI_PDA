<?php

namespace App\Http\Controllers;

use App\Models\Statistic;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StatisticController extends Controller
{
    // Προβολή στατιστικών για την τρέχουσα εβδομάδα
    public function showCurrentWeekStatistics(Request $request)
    {
        // Υπολογισμός της πρώτης και τελευταίας ημέρας της εβδομάδας
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        // Λήψη των στατιστικών για την τρέχουσα εβδομάδα
        $statistics = Statistic::with('menuItem')
            ->whereBetween('date', [$startOfWeek, $endOfWeek])
            ->orderBy('sold_count', 'desc')
            ->get()
            ->filter(fn($stat) => $stat->menuItem !== null);


        return view('statistics.index', compact('statistics'));
    }

    // Προβολή στατιστικών για τον τρέχοντα μήνα
    public function showCurrentMonthStatistics(Request $request)
    {
        // Φιλτράρισμα για τον τρέχοντα μήνα
        $statistics = Statistic::whereYear('date', Carbon::now()->year)
            ->whereMonth('date', Carbon::now()->month)
            ->orderBy('sold_count', 'desc')
            ->get();

        return view('statistics.index', compact('statistics'));
    }

    // Προβολή στατιστικών για τον προηγούμενο μήνα
    public function showPreviousMonthStatistics(Request $request)
    {
        // Λήψη στατιστικών για τον προηγούμενο μήνα
        $statistics = Statistic::whereBetween('date', [
            Carbon::now()->subMonth()->startOfMonth(), // Πρώτη μέρα του προηγούμενου μήνα
            Carbon::now()->subMonth()->endOfMonth() // Τελευταία μέρα του προηγούμενου μήνα
        ])
            ->orderBy('sold_count', 'desc')
            ->get();

        return view('statistics.index', compact('statistics'));
    }

    // Προβολή στατιστικών για οποιαδήποτε ημερομηνία που παρέχεται
    public function showStatisticsByDate(Request $request)
    {
        // Έλεγχος αν παρέχεται η ημερομηνία από το αίτημα
        $date = $request->input('date');

        // Έλεγχος αν υπάρχει ημερομηνία, αλλιώς χρησιμοποιούμε την τρέχουσα ημερομηνία
        $statistics = Statistic::whereDate('date', $date ?? Carbon::today())
            ->orderBy('sold_count', 'desc')
            ->get();

        return view('statistics.index', compact('statistics'));
    }

    // Προβολή στατιστικών για ένα συγκεκριμένο χρονικό διάστημα
    public function showStatisticsByPeriod(Request $request)
    {
        // Έλεγχος αν παρέχονται ημερομηνίες για το διάστημα
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth());

        // Λήψη στατιστικών για το επιθυμητό διάστημα
        $statistics = Statistic::whereBetween('date', [$startDate, $endDate])
            ->orderBy('sold_count', 'desc')
            ->get();

        return view('statistics.index', compact('statistics'));
    }
}
