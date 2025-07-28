<?php

namespace App\Http\Controllers;

use App\Models\Payment;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function edit(Payment $payment)
    {
        return view('payments.edit', compact('payment'));
    }

    public function update(Request $request, Payment $payment)
    {
        $request->validate([
            'method' => 'required|in:card,cash',
            'items' => 'array',
            'items.*' => 'exists:order_items,id',
        ]);

        $payment->update([
            'method' => $request->method,
            'paid_at' => now(),
            'amount' => $request->amount,
        ]);
        // Ενημέρωση των μη πληρωμένων order items
        $order = $payment->order;

        if ($request->has('items')) {
            foreach ($request->items as $itemId) {
                $item = $order->orderItems()->where('id', $itemId)->first();
                if ($item && !$item->is_paid) {
                    $item->payment_method = $request->method;
                    $item->is_paid = true;
                    $item->save();
                }
            }
        }


        // Αν έχουν πληρωθεί όλα τα είδη, ενημέρωσε και την παραγγελία
        if ($order->orderItems()->where('is_paid', false)->count() === 0) {
            $order->status = 'paid';
            $order->save();

            // Ενημέρωσε και το τραπέζι αν υπάρχει
            if ($order->table) {
                $order->table->status = 'paid';
                $order->table->save();
            }
        }

        return redirect()->route('tables.view')->with('success', 'Η πληρωμή ενημερώθηκε.');
    }
}
