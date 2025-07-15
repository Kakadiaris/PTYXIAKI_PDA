<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\MenuItem;
use App\Models\Payment;
use App\Models\OrderItem;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        if (in_array($user->role, ['admin', 'superuser'])) {
            $orders = \App\Models\Order::latest()->get(); // όλες οι παραγγελίες
        } else {
            $orders = \App\Models\Order::where('user_id', $user->id)->latest()->get(); // μόνο του χρήστη
        }
        return view('orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $menu_items = \App\Models\MenuItem::all();
        $tables = \App\Models\Table::all();
        return view('orders.create', compact('tables', 'menu_items'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'table_id' => 'required|exists:tables,id',
            'menu_items' => 'required|array|min:1',
            'menu_items.*.id' => 'required|exists:menu_items,id',
            'menu_items.*.quantity' => 'required|integer|min:1',
        ]);

        $total = 0;
        $order = new \App\Models\Order();
        $order->table_id = $request->table_id;
        $order->user_id = auth()->id(); // συνδέουμε τον σερβιτόρο
        $order->status = 'pending'; // αρχική κατάσταση
        $order->total = 0;
        $order->save();

        foreach ($request->menu_items as $menu_item_data) {
            $menuItem = MenuItem::findOrFail($menu_item_data['id']);
            $quantity = (int)$menu_item_data['quantity'];
            $subtotal = $menuItem->price * $quantity;

            // Δημιουργία εγγραφής στη συνδεδεμένη σχέση (order_items)
            OrderItem::create([
                'order_id' => $order->id,
                'menu_item_id' => $menuItem->id,
                'quantity' => $quantity,
                'price' => $menuItem->price,
                // 'subtotal' => $subtotal,
            ]);

            $total += $menuItem->price * $quantity;
        }

        // Ενημέρωση συνολικού ποσού
        $order->total = $total;
        $order->save();
        Payment::create([
            'order_id' => $order->id,
            'amount' => $total,
            'method' => null,
            'paid_at' => null,
        ]);

        return redirect()->route('orders.index')->with('success', 'Η παραγγελία δημιουργήθηκε.');
    }

    /**
     * Display the specified resource.
     */

    public function show(Order $order)
    {
        $order->load(['table', 'items.menuItem']); // Φορτώνουμε και τα relations
        return view('orders.show', compact('order'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function complete(Order $order)
{
    $order->status = 'paid';
    $order->save();

    return redirect()->back()->with('success', 'Η παραγγελία ολοκληρώθηκε.');
}
}
