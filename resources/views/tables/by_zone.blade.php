@extends('layouts.pda')

@section('content')
    <a href="{{ route('tables.create') }}" class="btn btn-primary mb-4">Νέο Τραπέζι</a>

    <div class="container text-center">
        <h2 class="mb-4">Τραπέζια</h2>

        <div class="row justify-content-center">
            @foreach ($tables as $table)
                <div class="col-6 col-md-3 mb-3 position-relative">
                    @php
                        $tableName = $table->zone->value . $table->number;
                    @endphp

                    @if ($table->status === 'free')
                        {{-- Κουμπί τραπεζιού με ενεργοποίηση modal --}}
                        <a href="javascript:void(0)" class="btn text-white w-100 py-4"
                           data-bs-toggle="modal"
                           data-bs-target="#tableModal{{ $table->id }}"
                           style="font-size: 22px; border-radius: 16px; background-color: #28a745;">
                            {{ $tableName }}
                        </a>

                        {{-- Modal δημιουργίας παραγγελίας --}}
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
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                            Ακύρωση
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        {{-- Μη διαθέσιμο τραπέζι --}}
                        <a href="#" class="btn text-white w-100 py-4"
                           style="font-size: 22px; border-radius: 16px;
                                  background-color:
                                  {{ $table->status === 'pending' ? '#ffc107' : '#dc3545' }};">
                            {{ $tableName }}
                        </a>
                    @endif

                    {{-- Κουμπί διαγραφής --}}
                    <form action="{{ route('tables.destroy', $table->id) }}" method="POST"
                          onsubmit="return confirm('Είσαι σίγουρος ότι θέλεις να διαγράψεις το τραπέζι {{ $tableName }};')"
                          class="position-absolute top-0 end-0 mt-1 me-1">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger px-2 py-1" title="Διαγραφή">
                            🗑
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    </div>
@endsection
