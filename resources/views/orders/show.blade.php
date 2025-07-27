@extends('layouts.pda')

@section('content')
    <div class="container">
        <h2>Παραγγελία #{{ $order->id }}</h2>

        <p><strong>Τραπέζι:</strong> {{ $order->table->zone->value }}{{ $order->table->number }}</p>
        <p><strong>Κατάσταση:</strong> {{ $order->status }}</p>
        <p><strong>Συνολικό Ποσό:</strong> €{{ number_format($order->total, 2) }}</p>

        <h4>Προϊόντα:</h4>
        <table class="table">
            <thead>
                <tr>
                    <th>Προϊόν</th>
                    <th>Ποσότητα</th>
                    <th>Τιμή</th>
                    <th>Σύνολο</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->items as $item)
                    <tr>
                        <td>{{ $item->menuItem->name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>€{{ number_format($item->price, 2) }}</td>
                        <td>€{{ number_format($item->price * $item->quantity, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{-- Έλεγχος κατάστασης --}}
        <div class="mt-4">
            @if ($order->status === 'paid')
                <div class="alert alert-success">
                    Η παραγγελία έχει ολοκληρωθεί.
                </div>
            @elseif ($order->status === 'completed')
                <div class="alert alert-success">
                    Η παραγγελία έχει ήδη σημανθεί ως ολοκληρωμένη.
                </div>
            @else
                <form action="{{ route('orders.complete', $order) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success">Ολοκλήρωση Παραγγελίας</button>
                </form>
            @endif
        </div>

        <div class="mt-3">
            <a href="{{ route('orders.edit', $order) }}" class="btn btn-warning">Επεξεργασία Παραγγελίας</a>
            <a href="{{ route('orders.index') }}" class="btn btn-secondary">Επιστροφή</a>
        </div>

    </div>
@endsection
