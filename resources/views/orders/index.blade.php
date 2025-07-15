@extends('layouts.pda')

@section('content')
    <div class="orders-wrapper">


        @if ($orders->count())
            @foreach ($orders as $order)
                <div class="order-box {{ $order->status === 'completed' ? 'order-completed' : '' }}">
                    <div class="order-items">
                        @foreach ($order->items as $item)
                            <div>{{ $item->quantity }} {{ $item->menuItem->name }}
                            </div>
                        @endforeach
                    </div>
                    <div class="order-actions">
                        <a href="{{ route('orders.show', $order) }}" class="btn btn-dark">Προβολή</a>
                        <form action="{{ route('orders.destroy', $order) }}" method="POST"
                            onsubmit="return confirm('Είσαι σίγουρος;');" class="mt-3">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Διαγραφή Παραγγελίας</button>
                        </form>

                        @if ($order->status === 'paid')
                            <span class="btn btn-dark disabled">Η παραγγελία έχει ολοκληρωθεί</span>
                        @elseif ($order->status === 'completed')
                            <span class="btn btn-dark disabled">Ολοκληρωμένη</span>
                        @else
                            <form action="{{ route('orders.complete', $order) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-dark">Ολοκλήρωση</button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        @else
            <p>Δεν υπάρχουν παραγγελίες.</p>
        @endif

        <div class="new-order-btn-wrapper">
            <a href="{{ route('orders.create') }}" class="btn btn-primary new-order-btn">Νέα Παραγγελία</a>
        </div>
    </div>
@endsection
