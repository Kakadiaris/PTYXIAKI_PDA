@extends('layouts.pda')

@section('content')
    <div class="container">
        <h2 class="mb-4">➕ Δημιουργία Κράτησης</h2>

        @if ($errors->any())
            <div class="alert alert-danger text-start">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('reservations.store') }}">
            @csrf

            <div class="mb-3">
                <label for="table_id" class="form-label">Τραπέζι</label>
                <select name="table_id" id="table_id" class="form-select" required>
                    <option value="">-- Επιλέξτε τραπέζι --</option>
                    @foreach ($tables as $table)
                        <option value="{{ $table->id }}" {{ old('table_id') == $table->id ? 'selected' : '' }}>
                            Τραπέζι {{ $table->number }} (Ζώνη {{ $table->zone->value }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="guest_count" class="form-label">Αριθμός Ατόμων</label>
                <input type="number" name="guest_count" id="guest_count" class="form-control" value="{{ old('guest_count') }}" required min="1">
            </div>

            <div class="mb-3">
                <label for="reservation_at" class="form-label">Ώρα Κράτησης</label>
                <input type="datetime-local" name="reservation_at" id="reservation_at" class="form-control" value="{{ old('reservation_at') }}" required>
            </div>

            <div class="mb-3">
                <label for="notes" class="form-label">Σχόλια (προαιρετικά)</label>
                <textarea name="notes" id="notes" class="form-control">{{ old('notes') }}</textarea>
            </div>

            <button type="submit" class="btn btn-success">Καταχώρηση</button>
            <a href="{{ route('tables.view') }}" class="btn btn-secondary">↩ Επιστροφή</a>
        </form>
    </div>
@endsection
