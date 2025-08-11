<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;

use App\Models\MenuItem;

use Illuminate\Http\Request;

class MenuItemController extends Controller
{

    public function index(Request $request)
    {
          $selected = $request->query('category');

    // Φέρε όλες τις μοναδικές κατηγορίες από τη ΒΔ
    $categories = \App\Models\MenuItem::query()
        ->select('category')
        ->whereNotNull('category')
        ->distinct()
        ->orderBy('category')
        ->pluck('category')
        ->toArray();

    // Προαιρετικό map για ελληνικά labels (αν έχεις σταθερά keys)
    $categoryLabels = [
        'cofee'  => 'Καφές',
        'snack'  => 'Σνακ',
        'drinks' => 'Ποτά',
        'ximos'  => 'Αναψυκτικά - Χυμοί',
    ];

    $query = \App\Models\MenuItem::query();
    if ($selected && in_array($selected, $categories, true)) {
        $query->where('category', $selected);
    }

    $items = $query->orderBy('name')->get();
    $groupedItems = $items->groupBy('category');

    return view('menu.index', compact('items','groupedItems','categories','categoryLabels','selected'));

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
