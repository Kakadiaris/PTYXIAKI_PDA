@extends('layouts.pda')

@section('content')
    <a href="{{ route('tables.create') }}" class="btn btn-primary mb-4">Νέο Τραπέζι</a>

    <div class="container text-center">
        <h2 class="mb-4">Τραπέζια</h2>

        <div class="row justify-content-center">
            @foreach ($tables as $table)
                @php
                    $tableName = $table->zone->value . $table->number;
                    // Χρώμα κουμπιού ανά status (όπως στο 2ο)
                    $bg =
                        $table->status === 'free'
                            ? '#28a745'
                            : ($table->status === 'pending'
                                ? '#ffc107'
                                : ($table->status === 'reserved'
                                    ? '#000000'
                                    : '#dc3545'));
                    // data-bs-target ανά status (όπως στο 2ο)
                    $dataTarget =
                        $table->status === 'free'
                            ? '#tableModal' . $table->id
                            : ($table->status === 'reserved' && $table->reservations->isNotEmpty()
                                ? '#reservationModal' . $table->id
                                : ($table->status === 'pending' || $table->status === 'paid'
                                    ? '#pendingOrPaidModal' . $table->id
                                    : ''));
                    $toggle = in_array($table->status, ['free', 'reserved', 'pending', 'paid']) ? 'modal' : '';
                @endphp

                <div class="col-6 col-md-3 mb-3 position-relative">
                    {{-- Κουμπί τραπεζιού (ίδια trigger λογική με το 2ο) --}}
                    <a href="javascript:void(0)" class="btn text-white w-100 py-4" data-bs-toggle="{{ $toggle }}"
                        data-bs-target="{{ $dataTarget }}"
                        style="font-size:22px; border-radius:16px; background-color: {{ $bg }};">
                        {{ $tableName }}
                    </a>

                    {{-- Προαιρετικά: κουμπί επιστροφής σε Free, αν ΔΕΝ είναι free (όπως στο 2ο) --}}
                    @if ($table->status !== 'free')
                        <form method="POST" action="{{ route('tables.free', $table->id) }}" class="mt-2">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-sm btn-outline-light w-100">
                                Επιστροφή σε Free
                            </button>
                        </form>
                    @endif

                    {{-- Κουμπί διαγραφής (ίδιο όπως είχες) --}}
                    <form action="{{ route('tables.destroy', $table->id) }}" method="POST"
                        onsubmit="return confirm('Είσαι σίγουρος ότι θέλεις να διαγράψεις το τραπέζι {{ $tableName }};')"
                        class="position-absolute top-0 end-0 mt-1 me-1">
                        @csrf
                        @method('DELETE')
                        @if (auth()->user()->role === 'superuser')
                            <button class="btn btn-sm btn-danger px-2 py-1" title="Διαγραφή">🗑</button>
                        @endif
                    </form>
                </div>

                {{-- Modal: free -> Νέα Παραγγελία (όπως στο 2ο) --}}
                @if ($table->status === 'free')
                    <div class="modal fade" id="tableModal{{ $table->id }}" tabindex="-1"
                        aria-labelledby="tableModalLabel{{ $table->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content text-start">
                                <div class="modal-header">
                                    <h5 class="modal-title fw-bold" id="tableModalLabel{{ $table->id }}">
                                        Νέα Παραγγελία για Τραπέζι {{ $tableName }}
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Κλείσιμο"></button>
                                </div>
                                <div class="modal-body">
                                    Θέλεις να δημιουργήσεις νέα παραγγελία για αυτό το τραπέζι;
                                </div>
                                <div class="modal-footer">
                                    <a href="{{ route('orders.create', ['table_id' => $table->id]) }}"
                                        class="btn btn-success">Νέα Παραγγελία</a>
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Ακύρωση</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Modal: pending/paid -> πληροφορίες παραγγελίας (όπως στο 2ο) --}}
                @if ($table->status === 'pending' || $table->status === 'paid')
                    @php
                        $order = $table->orders->last();
                    @endphp
                    <div class="modal fade" id="pendingOrPaidModal{{ $table->id }}" tabindex="-1"
                        aria-labelledby="pendingOrPaidModalLabel{{ $table->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content text-start">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="pendingOrPaidModalLabel{{ $table->id }}">
                                        Τραπέζι {{ $tableName }} - Κατάσταση: {{ ucfirst($table->status) }}
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Κλείσιμο"></button>
                                </div>
                                <div class="modal-body">
                                    @if ($table->status === 'pending')
                                        <p>Το τραπέζι είναι σε κατάσταση 'Pending'. Δεν έχει γίνει ακόμα ολοκλήρωση της
                                            παραγγελίας.</p>
                                    @elseif ($table->status === 'paid')
                                        <p>Η παραγγελία για αυτό το τραπέζι έχει ολοκληρωθεί και έχει πληρωθεί.</p>
                                    @endif

                                    @if ($table->orders->isNotEmpty())
                                        <h5>Πληροφορίες Παραγγελίας</h5>
                                        <p><strong>Όνομα που την έκανε:</strong>
                                            {{ $order?->user?->name ?? 'Άγνωστος χρήστης' }}</p>
                                        <p><strong>Σύνολο:</strong> {{ number_format($order?->total ?? 0, 2) }}€</p>
                                        <p><strong>Ώρα Παραγγελίας:</strong>
                                            {{ optional($order?->created_at)->format('H:i') }}</p>
                                    @else
                                        <p>Δεν υπάρχουν παραγγελίες για αυτό το τραπέζι.</p>
                                    @endif
                                </div>
                                <div class="modal-footer">
                                    @if ($order)
                                        <a href="{{ route('orders.show', $order) }}" class="btn btn-success">Προβολή
                                            Παραγγελίας</a>
                                    @endif
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Κλείσιμο</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Modal: reserved με κρατήσεις -> στοιχεία κράτησης (όπως στο 2ο) --}}
                @if ($table->status === 'reserved' && $table->reservations->isNotEmpty())
                    @php $reservation = $table->reservations->last(); @endphp
                    <div class="modal fade" id="reservationModal{{ $table->id }}" tabindex="-1"
                        aria-labelledby="reservationModalLabel{{ $table->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content text-start">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="reservationModalLabel{{ $table->id }}">
                                        Κράτηση για Τραπέζι {{ $tableName }}
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Κλείσιμο"></button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>Όνομα:</strong> {{ $reservation->name }}</p>
                                    <p><strong>Άτομα:</strong> {{ $reservation->guest_count }}</p>
                                    <p><strong>Ώρα:</strong>
                                        {{ \Carbon\Carbon::parse($reservation->datetime)->format('H:i') }}</p>
                                    <p><strong>Σημειώσεις:</strong> {{ $reservation->notes }}</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Κλείσιμο</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
@endsection
