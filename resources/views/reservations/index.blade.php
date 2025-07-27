@extends('layouts.pda')

@section('content')
    <div class="container">
        <h2 class="mb-4">📋 Λίστα Κρατήσεων</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($reservations->isEmpty())
            <p>Δεν υπάρχουν ενεργές κρατήσεις.</p>
        @else
            <div class="table-responsive">
                <table class="table table-bordered text-center">
                    <thead>
                        <tr>
                            <th>Τραπέζι</th>
                            <th>Άτομα</th>
                            <th>Ώρα</th>
                            <th>Χρήστης</th>
                            <th>Σχόλια</th>
                            @if (auth()->user()?->role === 'superuser')
                                <th>Ενέργειες</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reservations as $reservation)
                            <tr>

                                <td>{{ $reservation->table->zone->value }}{{ $reservation->table->number }}</td>
                                <td>{{ $reservation->guest_count }}</td>
                                <td>{{ \Carbon\Carbon::parse($reservation->reservation_at)->format('d/m/Y H:i') }}</td>
                                <td>{{ $reservation->user->name }}</td>
                                <td>{{ $reservation->notes ?? '-' }}</td>

                                @if (auth()->user()?->role === 'superuser')
                                    <td>
                                        <form action="{{ route('reservations.destroy', $reservation->id) }}" method="POST"
                                            onsubmit="return confirm('Είσαι σίγουρος ότι θέλεις να ακυρώσεις την κράτηση;')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">🗑 Ακύρωση</button>
                                        </form>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        {{-- 👇 Κουμπί δημιουργίας κράτησης --}}
        <div class="text-center mt-4">
            <a href="{{ route('reservations.create') }}" class="btn btn-primary">➕ Νέα Κράτηση</a>
        </div>

        <a href="{{ route('tables.view') }}" class="btn btn-secondary mt-4">↩ Επιστροφή στα Τραπέζια</a>
    </div>
@endsection
