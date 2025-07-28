@extends('layouts.pda')

@section('content')
    <div class="container text-center">
        <h2 class="mb-4">Τραπέζια</h2>

        <div class="row justify-content-center">
            @foreach ($tables as $table)
                <div class="col-6 col-md-3 mb-3 position-relative">

                    {{-- Κουμπί τραπεζιού --}}
                    <a href="javascript:void(0)" class="btn text-white w-100 py-4"
                        data-bs-toggle="{{ in_array($table->status, ['free', 'reserved']) ? 'modal' : '' }}"
                        data-bs-target="{{ $table->status === 'free' ? '#tableModal' . $table->id : ($table->status === 'reserved' && $table->reservations->isNotEmpty() ? '#reservationModal' . $table->id : '') }}"
                        style="font-size: 22px; border-radius: 16px;
          background-color:
              {{ $table->status === 'free' ? '#28a745' : ($table->status === 'pending' ? '#ffc107' : ($table->status === 'reserved' ? '#000000' : '#dc3545')) }};">
                        {{ $table->zone->value }}{{ $table->number }}
                    </a>

                    @if ($table->status !== 'free')
                        <form method="POST" action="{{ route('tables.free', $table->id) }}" class="mt-2">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-sm btn-outline-light w-100">
                                Επιστροφή σε Free
                            </button>
                        </form>
                    @endif



                    {{-- Κουμπί διαγραφής μόνο για superuser --}}
                    @if (auth()->user()->role === 'superuser')
                        <form action="{{ route('tables.destroy', $table->id) }}" method="POST"
                            onsubmit="return confirm('Είσαι σίγουρος ότι θέλεις να διαγράψεις το τραπέζι {{ $table->zone->value }}{{ $table->number }};')"
                            class="position-absolute top-0 end-0 mt-1 me-1">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger px-2 py-1" title="Διαγραφή">
                                🗑
                            </button>
                        </form>
                    @endif
                </div>

                {{-- Modal δημιουργίας παραγγελίας για free τραπέζι --}}
                @if ($table->status === 'free')
                    <div class="modal fade" id="tableModal{{ $table->id }}" tabindex="-1"
                        aria-labelledby="tableModalLabel{{ $table->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content text-start">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="tableModalLabel{{ $table->id }}">
                                        Νέα Παραγγελία για Τραπέζι {{ $table->zone->value }}{{ $table->number }}
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Κλείσιμο"></button>
                                </div>
                                <div class="modal-body">
                                    Θέλεις να δημιουργήσεις νέα παραγγελία για αυτό το τραπέζι;
                                </div>
                                <div class="modal-footer">
                                    <a href="{{ route('orders.create', ['table_id' => $table->id]) }}"
                                        class="btn btn-success"> Νέα Παραγγελία</a>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                        Ακύρωση
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Modal εμφάνισης για κρατημένα τραπέζια --}}
                @if ($table->status === 'reserved' && $table->reservations->isNotEmpty())
                    @php
                        $reservation = $table->reservations->last();
                    @endphp
                    <div class="modal fade" id="reservationModal{{ $table->id }}" tabindex="-1"
                        aria-labelledby="reservationModalLabel{{ $table->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content text-start">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="reservationModalLabel{{ $table->id }}">
                                        Κράτηση για Τραπέζι {{ $table->zone->value }}{{ $table->number }}
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
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                        Κλείσιμο
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
        <a href="{{ route('tables.create') }}" class="btn btn-primary mb-4"> Νέο Τραπέζι</a>
    </div>
@endsection
