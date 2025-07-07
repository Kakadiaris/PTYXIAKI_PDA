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
}
