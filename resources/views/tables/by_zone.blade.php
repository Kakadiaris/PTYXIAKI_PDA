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
                        <button class="btn text-white w-100 py-4 open-modal-btn"
                            style="font-size: 22px; border-radius: 16px; background-color: #28a745;"
                            data-table-id="{{ $table->id }}" data-table-name="{{ $tableName }}">
                            {{ $tableName }}
                        </button>
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

    {{-- Modal για επιβεβαίωση δημιουργίας παραγγελίας --}}
    <div class="modal fade" id="orderConfirmModal" tabindex="-1" aria-labelledby="orderModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="orderModalLabel">Νέα Παραγγελία</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Κλείσιμο"></button>
                </div>
                <div class="modal-body">
                    <p id="orderModalText">Θέλεις να δημιουργήσεις νέα παραγγελία για αυτό το τραπέζι;</p>
                </div>
                <div class="modal-footer">
                    <a id="confirmOrderBtn" href="#" class="btn btn-success">Νέα Παραγγελία</a>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Ακύρωση</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = new bootstrap.Modal(document.getElementById('orderConfirmModal'));
            const modalTitle = document.getElementById('orderModalLabel');
            const modalText = document.getElementById('orderModalText');
            const confirmBtn = document.getElementById('confirmOrderBtn');

            document.querySelectorAll('.open-modal-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const tableName = this.dataset.tableName;
                    const tableId = this.dataset.tableId;

                    modalTitle.innerText = `Νέα Παραγγελία για Τραπέζι ${tableName}`;
                    modalText.innerText =
                        `Θέλεις να δημιουργήσεις νέα παραγγελία για αυτό το τραπέζι;`;
                    confirmBtn.href = `/orders/create?table_id=${tableId}`;
                    modal.show();
                });
            });
        });
    </script>
@endsection
