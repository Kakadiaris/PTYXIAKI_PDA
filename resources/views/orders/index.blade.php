@extends('layouts.app')

@section('content')
    <h1>Οι παραγγελίες μου</h1>

    @if ($orders->count())
        <ul>
            @foreach ($orders as $order)
                <li>
                    #{{ $order->id }} | Τραπέζι: {{ $order->table_id }} | Κατάσταση: {{ $order->status }}
                </li>
            @endforeach
        </ul>
    @else
        <p>Δεν υπάρχουν παραγγελίες.</p>
    @endif
@endsection
