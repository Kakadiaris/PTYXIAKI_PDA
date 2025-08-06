<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Statistic;

class SuperuserController extends Controller
{
    public function superuserDashboard()
    {
        $startDate = now()->startOfWeek();
        $endDate = now()->endOfWeek();

        $statistics = Statistic::with('menuItem')
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('sold_count', 'desc')
            ->whereHas('menuItem')
            ->get();

        $labels = $statistics->pluck('menuItem.name');
        $sales = $statistics->pluck('sold_count');

        return view('superuser.dashboard', compact('statistics', 'labels', 'sales', 'startDate', 'endDate'));
    }
    public function index()
    {
        return view('superuser.dashboard');
    }
}
