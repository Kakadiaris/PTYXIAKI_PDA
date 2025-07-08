<?php

namespace App\Http\Controllers;

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
            'zone'=>'required|string|max:10',
            'number'=>'required|integer',
            'status'=>'required|in:free,pending,paid'
        ]);
        Table::create([
            'zone' => $request->zone,
            'number'=>$request->number,
            'status'=>$request->status,
        ]);
        return redirect()->route('tables.view')->with('success','Tο τραπέζι δημιουργήθηκε');
    }
    public function create()
{
    return view('tables.create');
}

}
