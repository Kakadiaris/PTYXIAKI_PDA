@extends('layouts.pda')

@section('content')
    <div class="container">
        <h2 class="mb-4">Δημιουργία Τραπεζιού</h2>

        @if ($errors->any())
            <div class="alert alert-danger text-start">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('tables.store') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">Ζώνη</label>
                <select name="zone_id" class="form-control" required>
                    <option value="" disabled selected>Επιλέξτε ζώνη</option>
                    @foreach ($zones as $zone)
                        <option value="{{ $zone->id }}">{{ $zone->value }}</option>
                    @endforeach
                </select>

            </div>

            <div class="mb-3">
                <label class="form-label">Αριθμός Τραπεζιού</label>
                <input type="number" name="number" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Κατάσταση</label>
                <select name="status" class="form-select" required>
                    <option value="free">Διαθέσιμο</option>
                    <option value="pending">Σε εξέλιξη</option>
                    <option value="paid">Πληρωμένο</option>
                </select>
            </div>

            <button type="submit" class="btn btn-success">Καταχώρηση</button>
            <a href="{{ route('tables.view') }}" class="btn btn-secondary">↩ Επιστροφή</a>
        </form>
    </div>
@endsection
