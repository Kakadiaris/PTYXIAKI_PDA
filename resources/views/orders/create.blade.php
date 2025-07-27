@extends('layouts.pda')

@section('content')
    <div class="container">
        <h2>Δημιουργία Παραγγελίας</h2>

        <form method="POST" action="{{ route('orders.store') }}">
            @csrf

            <!-- Τραπέζι -->
            <div class="mb-3">
                <label for="table_id" class="form-label">Τραπέζι</label>
                <select name="table_id" class="form-select" required>
                    @foreach ($tables as $table)
                        <option value="{{ $table->id }}">{{ $table->zone->value }}{{ $table->number }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Menu Items (repeater) -->
            <div id="menu-items-container">
                <div class="menu-item-row mb-3">
                    <select name="menu_items[0][id]" class="form-select d-inline-block w-50" required>
                        @foreach ($menu_items as $item)
                            <option value="{{ $item->id }}">{{ $item->name }} - €{{ $item->price }}</option>
                        @endforeach
                    </select>
                    <input type="number" name="menu_items[0][quantity]" class="form-control d-inline-block w-25"
                        min="1" value="1" required>
                    <button type="button" class="btn btn-danger btn-sm remove-item">✖</button>
                </div>
            </div>

            <button type="button" class="btn btn-secondary" id="add-item">+ Προσθήκη Προϊόντος</button>
            <br>

            <button type="submit" class="btn btn-primary mt-3">Καταχώρηση</button>
        </form>

    </div>
@endsection
<script>
document.addEventListener('DOMContentLoaded', function () {
    let itemIndex = 1;

    document.getElementById('add-item').addEventListener('click', function () {
        const container = document.getElementById('menu-items-container');
        const newRow = document.createElement('div');
        newRow.classList.add('menu-item-row', 'mb-3');

        newRow.innerHTML = `
            <select name="menu_items[${itemIndex}][id]" class="form-select d-inline-block w-50" required>
                @foreach ($menu_items as $item)
                    <option value="{{ $item->id }}">{{ $item->name }} - €{{ $item->price }}</option>
                @endforeach
            </select>
            <input type="number" name="menu_items[${itemIndex}][quantity]" class="form-control d-inline-block w-25" min="1" value="1" required>
            <button type="button" class="btn btn-danger btn-sm remove-item">✖</button>
        `;

        container.appendChild(newRow);
        itemIndex++;
    });

    // Αφαίρεση προϊόντος
    document.getElementById('menu-items-container').addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-item')) {
            e.target.closest('.menu-item-row').remove();
        }
    });
});
</script>

