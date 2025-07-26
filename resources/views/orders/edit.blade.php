@extends('layouts.pda')

@section('content')
<div class="container">
    <h2>✏️ Επεξεργασία Παραγγελίας #{{ $order->id }}</h2>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('orders.update', $order->id) }}">
        @csrf
        @method('PUT')

        {{-- Τραπέζι --}}
        <div class="mb-3">
            <label for="table_id" class="form-label">Τραπέζι</label>
            <select name="table_id" class="form-select" required>
                @foreach($tables as $table)
                    <option value="{{ $table->id }}" {{ $table->id == $order->table_id ? 'selected' : '' }}>
                        Τραπέζι #{{ $table->number }} (Ζώνη {{ $table->zone }})
                    </option>
                @endforeach
            </select>
        </div>

        <hr>

        {{-- Είδη Παραγγελίας --}}
        <h5>Είδη Παραγγελίας</h5>
        <div id="menu-items-container">
            @foreach($order->items as $index => $item)
                <div class="row menu-item-row mb-2">
                    <div class="col-md-6">
                        <select name="menu_items[{{ $index }}][id]" class="form-select" required>
                            <option value="">Επιλέξτε είδος</option>
                            @foreach($menu_items as $menu_item)
                                <option value="{{ $menu_item->id }}" {{ $menu_item->id == $item->menuItem->id ? 'selected' : '' }}>
                                    {{ $menu_item->name }} (€{{ number_format($menu_item->price, 2) }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="number" name="menu_items[{{ $index }}][quantity]" class="form-control" value="{{ $item->quantity }}" min="1" required>
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-danger remove-item">✖</button>
                    </div>
                </div>
            @endforeach
        </div>

        <button type="button" id="add-menu-item" class="btn btn-secondary mt-2">➕ Προσθήκη Είδους</button>

        <hr>

        <button type="submit" class="btn btn-success">💾 Αποθήκευση</button>
        <a href="{{ route('orders.index') }}" class="btn btn-secondary">↩ Επιστροφή</a>
    </form>
    @if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $error)
               <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

</div>

{{-- JS για προσθήκη/διαγραφή ειδών --}}
<script>
let itemIndex = {{ count($order->items) }};

document.getElementById('add-menu-item').addEventListener('click', function () {
    const container = document.getElementById('menu-items-container');
    const row = document.createElement('div');
    row.classList.add('row', 'menu-item-row', 'mb-2');

    row.innerHTML = `
        <div class="col-md-6">
            <select name="menu_items[${itemIndex}][id]" class="form-select" required>
                <option value="">Επιλέξτε είδος</option>
                @foreach($menu_items as $menu_item)
                    <option value="{{ $menu_item->id }}">{{ $menu_item->name }} (€{{ number_format($menu_item->price, 2) }})</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <input type="number" name="menu_items[${itemIndex}][quantity]" class="form-control" value="1" min="1" required>
        </div>
        <div class="col-md-3">
            <button type="button" class="btn btn-danger remove-item">✖</button>
        </div>
    `;
    container.appendChild(row);
    itemIndex++;
});

// Διαγραφή γραμμής
document.addEventListener('click', function (e) {
    if (e.target.classList.contains('remove-item')) {
        e.preventDefault();
        e.target.closest('.menu-item-row').remove();
    }
});
</script>
@endsection
