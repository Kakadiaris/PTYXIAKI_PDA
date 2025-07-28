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

        $tables = Table::select('tables.*')
            ->join('zones', 'tables.zone_id', '=', 'zones.id')
            ->orderBy('zones.value')       // ταξινόμηση με βάση το A, B, C
            ->orderBy('tables.number')     // και μετά αριθμητικά
            ->with(['zone', 'reservations'])                 // φόρτωσε και τη σχέση
            ->get();
        return view('tables.index', compact('tables'));
    }
    public function store(Request $request)
    {


        $request->validate([
            'zone_id' => 'required|exists:zones,id',
            'number' => 'required|integer',
            'status' => 'required|in:free,pending,paid'
        ]);

        // Βρες τη ζώνη με βάση το value
        $zone = Zone::find($request->zone_id);

        if (!$zone) {
            return back()->withErrors(['zone' => 'Η ζώνη που έδωσες δεν υπάρχει.'])->withInput();
        }

        // Έλεγχος αν υπάρχει ήδη τραπέζι με ίδιο number στη ζώνη
        $exists = Table::where('zone_id', $zone->id)
            ->where('number', $request->number)
            ->exists();

        if ($exists) {
            return back()->withErrors(['zone' => 'Το τραπέζι αυτό υπάρχει ήδη στη ζώνη.'])->withInput();
        }

        // Δημιουργία τραπεζιού με σωστό zone_id
        Table::create([
            'zone_id' => $request->zone_id,
            'number' => $request->number,
            'status' => $request->status,
        ]);

        // Προαιρετικό count πεδίο
        $zone->increment('tables_count');

        return redirect()->route('tables.view')->with('success', 'Tο τραπέζι δημιουργήθηκε');
    }

    public function create()
    {
        $zones = Zone::orderBy('value')->get();
        return view('tables.create', compact('zones'));
    }
    public function destroy(Table $table)
    {
        $table->delete();
        return redirect()->route('tables.view')->with('success', 'Το τραπέζι διαγράφηκε.');
    }
    public function byZone(Zone $zone)
    {

        $tables = $zone->tables()->get(); // Φέρνει τα τραπέζια της ζώνης
        return view('tables.by_zone', compact('tables', 'zone'));
    }
    public function markAsFree(Table $table)
    {
        $table->status = 'free';
        $table->save();

        return redirect()->back()->with('success', 'Το τραπέζι άλλαξε σε "ελεύθερο".');
    }
}
