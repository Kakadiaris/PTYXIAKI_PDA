<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::with(['table.zone', 'user'])
            ->latest()
            ->get();

        return view('reservations.index', compact('reservations'));
    }

    public function create()
    {
        $tables = \App\Models\Table::with('zone')->where('status', '!=', 'reserved')->get();

        return view('reservations.create', compact('tables'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'table_id' => 'required|exists:tables,id',
            'guest_count' => 'required|integer|min:1',
            'reservation_at' => 'required|date|after_or_equal:now',
            'notes' => 'nullable|string',
            'name'  => 'required|string',
        ]);

        $table = Table::find($request->table_id);

        // Έλεγχος αν το τραπέζι είναι ήδη κρατημένο
        if ($table->status === 'reserved') {
            return back()->withErrors(['table_id' => 'Το τραπέζι είναι ήδη κρατημένο.']);
        }

        // Δημιουργία κράτησης
        $reservation = Reservation::create([
            'table_id' => $table->id,
            'user_id' => Auth::id(),
            'guest_count' => $request->guest_count,
            'reservation_at' => $request->reservation_at,
            'notes' => $request->notes,
            'name' => $request->name,
        ]);

        // Ενημέρωση τραπεζιού ως reserved
        // $table->update(['status' => 'reserved']);

        return redirect()->route('tables.view')->with('success', 'Η κράτηση καταχωρήθηκε.');
    }
    public function destroy(Reservation $reservation)
    {
        // Soft delete κράτησης
        $reservation->delete();

        // Επαναφορά τραπεζιού σε "free" μόνο αν είναι "reserved"
        if ($reservation->table && $reservation->table->status === 'reserved') {
            $reservation->table->update(['status' => 'free']);
        }

        return redirect()->route('reservations.index')->with('success', 'Η κράτηση ακυρώθηκε.');
    }
}
