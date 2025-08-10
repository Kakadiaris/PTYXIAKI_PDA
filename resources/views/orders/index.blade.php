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

                        @if ($order->status === 'paid')
                            <span class="btn btn-dark disabled">Ολοκληρωμένη</span>
                        @elseif ($order->status === 'completed')
                            <span class="btn btn-dark disabled">Ολοκληρωμένη</span>
                        @else
                            <form action="{{ route('orders.complete', $order) }}" method="POST" class="pan_form">
                                @csrf
                                <button type="submit" class="btn btn-success">Ολοκλήρωση</button>
                            </form>
                        @endif
                        @php
                            $userRole = auth()->user()->role;
                        @endphp

                        @if (($userRole === 'kitchen' && !$order->kitchen_ready_at) || ($userRole === 'bar' && !$order->bar_ready_at))
                            <form action="{{ route('orders.markReady', $order->id) }}" method="POST" class="pan_form">
                                @csrf
                                <button type="submit" class="btn btn-info">
                                    Ολοκλήρωση {{ $userRole === 'kitchen' ? 'Kitchen' : 'Bar' }}
                                </button>
                            </form>
                        @endif
                        @php
                            $userRole = auth()->user()->role;
                        @endphp

                        @if ($order->kitchen_ready_at)
                            <span class="btn btn-success disabled">Kitchen έτοιμο</span>
                        @endif

                        @if ($order->bar_ready_at)
                            <span class="btn btn-success disabled">Bar έτοιμο</span>
                        @endif
                        @if ($userRole === 'superuser')
                            <form action="{{ route('orders.destroy', $order) }}" method="POST"
                                onsubmit="return confirm('Είσαι σίγουρος;');"
                                class="pan_form mt-3>
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                class="btn btn-danger">Διαγραφή Παραγγελίας</button>
                            </form>
                        @endif


                    </div>

                </div>
            @endforeach
        @else
            <p>Δεν υπάρχουν παραγγελίες.</p>
        @endif
        @php
            $role = auth()->user()->role;
        @endphp
        @if (!in_array($role, ['kitchen', 'bar']))
            <div class="new-order-btn-wrapper">
                <a href="{{ route('orders.create') }}" class="btn btn-primary new-order-btn">Νέα Παραγγελία</a>
            </div>
        @endif
    </div>
@endsection
