@extends('layouts.pda')
@section('content')
    <h2>Ορισμός τρόπου πληρωμής</h2>

    <form method="POST" action="{{ route('payments.update', $payment) }}">
        @csrf
        @method('PUT')

        <label for="method">Τρόπος Πληρωμής:</label>
        <select name="method" id="method" required>
            <option value="">-- Επιλογή --</option>
            <option value="cash" {{ $payment->method === 'cash' ? 'selected' : '' }}>Μετρητά</option>
            <option value="card" {{ $payment->method === 'card' ? 'selected' : '' }}>Κάρτα</option>
        </select>

        <hr>

        <h4>Επιλογή προϊόντων Πληρωμής</h4>

        @foreach ($payment->order->items as $item)
            <div class="form-check">
                <input class="form-check-input item-checkbox" type="checkbox" name="items[]" value="{{ $item->id }}"
                    data-price="{{ $item->price * $item->quantity }}" id="item-{{ $item->id }}"
                    {{ $item->is_paid ? 'checked disabled' : '' }}>


                <label class="form-check-label" for="item-{{ $item->id }}">
                    {{ $item->menuItem->name }} ({{ $item->quantity }} x {{ number_format($item->price, 2) }}€)
                </label>
                @if ($item->is_paid)
                    <span class="badge bg-success ms-2">Πληρωμένο</span>
                @endif

            </div>
        @endforeach

        <input type="hidden" name="amount" id="payment-amount" value="0">
        <p class="mt-2">Ποσό πληρωμής: <strong><span id="calculated-amount">0.00€</span></strong></p>

        <button type="submit" class="btn btn-success mt-3">Ολοκλήρωση Πληρωμής</button>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('.item-checkbox');
            const amountField = document.getElementById('payment-amount');
            const amountDisplay = document.getElementById('calculated-amount');

            function updateAmount() {
                let total = 0;
                checkboxes.forEach(cb => {
                    if (cb.checked) {
                        total += parseFloat(cb.dataset.price);
                    }
                });
                amountField.value = total.toFixed(2);
                amountDisplay.textContent = total.toFixed(2) + '€';
            }

            checkboxes.forEach(cb => cb.addEventListener('change', updateAmount));
        });
    </script>
@endsection
