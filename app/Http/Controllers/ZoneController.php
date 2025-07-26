<?php

namespace App\Http\Controllers;

use App\Models\Zone;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class ZoneController extends Controller
{
    public function create()
    {
        return view('zones.create');
    }
    public function index()
    {
        $zones = Zone::all();
        return view('zones.index', compact('zones'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'value' => 'required|string|unique:zones,value|max:10'
        ]);

        Zone::create(['value' => $request->value]);

        return redirect()->route('zones.create')->with('success', 'Η ζώνη δημιουργήθηκε.');
    }
    public function destroy(Zone $zone)
    {
        $user = auth()->user();

        // Μόνο superuser μπορεί να διαγράψει
        if ($user->role !== 'superuser') {
            abort(403, 'Δεν έχεις δικαίωμα διαγραφής ζώνης.');
        }

        $zone->delete(); // Soft delete (αν το έχεις ενεργό στο μοντέλο)

        return redirect()->route('zones.index')->with('success', 'Η ζώνη διαγράφηκε.');
    }
}
