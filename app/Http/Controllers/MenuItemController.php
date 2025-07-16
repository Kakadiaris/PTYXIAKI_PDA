<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;

use App\Models\MenuItem;

use Illuminate\Http\Request;

class MenuItemController extends Controller
{

    public function index()
    {
        $items = \App\Models\MenuItem::all();
        return view('menu.index', compact('items'));
    }
    public function create()
    {
        return view('menu.create');
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'target' => 'required|in:kitchen,bar',
            'category'=> 'required|string',
        ]);


        MenuItem::create($validated);

        return redirect()->route('menu.index')->with('success', 'Το προϊόν προστέθηκε με επιτυχία.');
    }
    public function destroy(MenuItem $menu)
{
    $menu->delete(); // αν έχεις soft deletes, θα γίνει soft delete
    return redirect()->route('menu.index')->with('success', 'Το προϊόν διαγράφηκε επιτυχώς.');
}
}
