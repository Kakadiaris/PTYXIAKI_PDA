<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        $tables = \App\Models\Table::all();
        return view('orders.create', compact('tables'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'table_id' => 'required|exists:tables,id',
        ]);

        $order = new \App\Models\Order();
        $order->table_id = $request->table_id;
        $order->user_id = auth()->id(); // συνδέουμε τον σερβιτόρο
        $order->status = 'pending'; // αρχική κατάσταση
        $order->save();

        return redirect()->route('orders.index')->with('success', 'Η παραγγελία δημιουργήθηκε.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
}
