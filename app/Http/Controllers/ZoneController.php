<?php

namespace App\Http\Controllers;

use App\Models\Zone;
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
}
