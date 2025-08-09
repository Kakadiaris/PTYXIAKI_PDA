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
        $role = $user->role;

        if (in_array($user->role, ['admin', 'superuser', 'kitchen', 'bar'])) {
            $orders = \App\Models\Order::latest()->get(); // όλες οι παραγγελίες
        } else {
            $orders = \App\Models\Order::where('user_id', $user->id)->latest()->get(); // μόνο του χρήστη
        }
        if (in_array($role, ['bar', 'kitchen'])) {
            foreach ($orders as $order) {
                $order->items = $order->items->filter(function ($item) use ($role) {
                    return $item->menuItem && $item->menuItem->target === $role;
                });
            }
            // Εδώ κρατάμε ΜΟΝΟ παραγγελίες που έχουν τουλάχιστον 1 item για τον ρόλο
            $orders = $orders->filter(function ($order) {
                return $order->items->count() > 0;
            })->values(); // reset keys
        }
        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        $menu_items = \App\Models\MenuItem::all();
        $tables = \App\Models\Table::all();
        return view('orders.create', compact('tables', 'menu_items'));
    }


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
        $order->status = 'pending'; // αρχική κατάσταση για παραγγελια
        $order->total = 0;
        if ($order->table) {
            $order->table->status = 'pending'; // αρχική κατάσταση τραπεζιού
            $order->table->save();
        }
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
    public function edit(Order  $order)
    {

        $order->load(['items.MenuItem']); // Φορτώνει τα είδη της παραγγελιας
        $menu_items = MenuItem::all(); // Ολα τα είδη για επιλογή
        $tables = \App\Models\Table::all(); // Oλα τα τραπέζια για αλλαγή

        return view('orders.edit', compact('order', 'menu_items', 'tables'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {

        $request->validate([
            'table_id' => 'required|exists:tables,id',
            'menu_items' => 'required|array|min:1',
            'menu_items.*.id' => 'required|exists:menu_items,id',
            'menu_items.*.quantity' => 'required|integer|min:1',
        ]);

        //Παιρνω το status της παραγγελιας
        $wasPaid = $order->status === 'paid';

        $order->table_id = $request->table_id;
        $order->save();

        // Διαγραφή προηγούμενων αντικειμένων
        $order->items()->delete();

        $total = 0;
        $hasKitchenItems = false;
        $hasBarItems = false;

        foreach ($request->menu_items as $menu_item_data) {
            $menuItem = MenuItem::findOrFail($menu_item_data['id']);
            $quantity = (int) $menu_item_data['quantity'];
            $subtotal = $menuItem->price * $quantity;

            OrderItem::create([
                'order_id' => $order->id,
                'menu_item_id' => $menuItem->id,
                'quantity' => $quantity,
                'price' => $menuItem->price,
            ]);

            $total += $subtotal;

            // Έλεγχος αν είναι για kitchen ή bar
            if ($menuItem->target === 'kitchen') {
                $hasKitchenItems = true;
            }
            if ($menuItem->target === 'bar') {
                $hasBarItems = true;
            }
        }

        // Reset των flags μόνο αν βρέθηκαν σχετικά items
        if ($hasKitchenItems) {
            $order->kitchen_ready_at = null;
        }
        if ($hasBarItems) {
            $order->bar_ready_at = null;
        }
        // αν ήταν paid και έγινε edit, γυρνά σε pending
        if ($wasPaid) {
            $order->status  = 'pending';
            // και το τραπέζι πίσω σε pending
            if ($order->table) {
                $order->table->status = 'pending';
                $order->table->save();
            }
        }
        
        $order->total = $total;
        $order->save();

        // Αν έχει ήδη δημιουργηθεί πληρωμή, ενημερώνουμε ποσό
        if ($order->payment) {
            $order->payment->update(['amount' => $total]);
        }

        return redirect()->route('orders.index')->with('success', 'Η παραγγελία ενημερώθηκε.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        $order->delete();
        if ($order->table) {
            $order->table->status = 'free';
            $order->table->save();
        }

        return redirect()->route('orders.index')->with('success', 'Η παραγγελία διαγράφηκε.');
    }
    public function complete(Order $order)
    {
        if (!$order->payment || !$order->payment->method) {
            return redirect()->route('payments.edit', $order->payment->id)
                ->with('error', 'Πρέπει να δηλωθεί τρόπος πληρωμής πριν την ολοκλήρωση.');
        }

        $allPaid = $order->orderItems()->where('is_paid', false)->count() === 0;

        if (! $allPaid) {
            return redirect()->route('payments.edit', $order->payment->id)
                ->with('error', 'Δεν έχουν πλησωθεί όλα τα είδη της παραγγελίας');
        }

        $order->status = 'paid';
        $order->save();

        // Αλλαγή του status του τραπεζιού σε 'paid'
        if ($order->table) {
            $order->table->status = 'paid';
            $order->table->save();
        }
        return redirect()->back()->with('success', 'Η παραγγελία ολοκληρώθηκε.');
    }

    public function markReady(Request $request, $orderId)
    {
        $user = auth()->user();
        $order = Order::findOrFail($orderId);

        if ($user->role === 'kitchen') {
            $order->kitchen_ready_at = now();
        }

        if ($user->role === 'bar') {
            $order->bar_ready_at = now();
        }

        $order->save();

        return response()->json(['success' => true]);
    }
}
