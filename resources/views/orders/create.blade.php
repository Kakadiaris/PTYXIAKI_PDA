@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Δημιουργία Παραγγελίας</h2>

    <form method="POST" action="{{ route('orders.store') }}">
        @csrf

        <!-- Π.χ. Επιλογή τραπεζιού -->
        <div class="mb-3">
            <label for="table_id" class="form-label">Τραπέζι</label>
            <select name="table_id" class="form-select" required>
                @foreach ($tables as $table)
                    <option value="{{ $table->id }}">{{ $table->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Μπορείς να προσθέσεις παραπάνω στοιχεία αργότερα -->

        <button type="submit" class="btn btn-primary">Καταχώρηση</button>
    </form>
</div>
@endsection
