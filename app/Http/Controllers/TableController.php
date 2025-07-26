<?php

namespace App\Http\Controllers;

use App\Models\Zone;
use App\Models\Table;
use Illuminate\Http\Request;

class TableController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Επιτρέπουμε μόνο συγκεκριμένους ρόλους να δουν τα τραπέζια
        if (!in_array($user->role, ['waiter', 'admin', 'superuser'])) {
            abort(403, 'Δεν έχεις πρόσβαση σε αυτή τη σελίδα.');
        }

        $tables = Table::orderBy('zone')->orderBy('number')->get();

        return view('tables.index', compact('tables'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'zone' => 'required|string|max:10',
            'number' => 'required|integer',
            'status' => 'required|in:free,pending,paid'
        ]);
        $exists = Table::where('zone', $request->zone)
            ->where('number', $request->number)
            ->exists();
        if ($exists) {
            return back()->withErrors(['zone' => 'Το τραπέζι αυτό υπάρχει ήδη'])->withInput();
        }
        // Βρες τη ζώνη με βάση το όνομα (π.χ. "Ν")
        $zone = Zone::where('value', $request->zone)->first();

        if (!$zone) {
            return back()->withErrors(['zone' => 'Η ζώνη που έδωσες δεν υπάρχει.'])->withInput();
        }

        // Έλεγχος αν υπάρχει ήδη τραπέζι στη ζώνη αυτή με το ίδιο νούμερο
        $exists = Table::where('zone_id', $zone->id)
            ->where('number', $request->number)
            ->exists();

        if ($exists) {
            return back()->withErrors(['zone' => 'Το τραπέζι αυτό υπάρχει ήδη στη ζώνη.'])->withInput();
        }

        Table::create([
            'zone_id' => $zone->id,
            'zone' => $request->zone,
            'number' => $request->number,
            'status' => $request->status,
        ]);
        $zone->increment('tables_count');
        return redirect()->route('tables.view')->with('success', 'Tο τραπέζι δημιουργήθηκε');
    }
    public function create()
    {
    $zones = Zone::orderBy('value')->get();
    return view('tables.create', compact('zones'));    }
}
