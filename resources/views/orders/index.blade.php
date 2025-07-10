@extends('layouts.pda')

@section('content')
    <h1>Οι παραγγελίες μου</h1>


    @if ($orders->count())
        <ul>
            @foreach ($orders as $order)
                <li>
                    #{{ $order->id }} | Τραπέζι: {{ $order->table_id }} | Κατάσταση: {{ $order->status }}
                    <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-primary"> Προβολή</a>

                </li>
            @endforeach
        </ul>
        
    @else
        <p>Δεν υπάρχουν παραγγελίες.</p>
    @endif
@endsection
