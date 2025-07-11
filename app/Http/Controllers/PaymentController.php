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
        ]);

        $payment->update([
            'method' => $request->method,
            'paid_at' => now(),
        ]);
        return redirect()->route('tables.view')->with('success', 'Η πληρωμή ενημερώθηκε.');
    }
}
