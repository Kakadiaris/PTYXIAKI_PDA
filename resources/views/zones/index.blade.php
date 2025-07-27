@extends('layouts.pda')

@section('content')
    <div class="container">
        <h2 class="mb-4">Λίστα Ζωνών</h2>

        {{-- Επιτυχές μήνυμα --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- Κουμπί για δημιουργία --}}
        <a href="{{ route('zones.create') }}" class="btn btn-primary mb-3">➕ Προσθήκη Ζώνης</a>

        {{-- Πίνακας Ζωνών --}}
        @if ($zones->count())
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Όνομα Ζώνης</th>
                        <th>Ημ/νία Δημιουργίας</th>
                        <th>Τραπέζια</th>
                        @if (auth()->user()->role === 'superuser')
                            <th>Ενέργειες</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($zones as $zone)
                        <tr>
                            <td>{{ $zone->id }}</td>
                            <td>{{ $zone->value }}</td>
                            <td>{{ $zone->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ route('tables.byZone', $zone->value) }}" class="btn btn-sm btn-secondary">
                                    🪑 Δες Τραπέζια
                                </a>
                            </td>

                            @if (auth()->user()->role === 'superuser')
                                <td>
                                    <form action="{{ route('zones.destroy', $zone->value) }}" method="POST"
                                        onsubmit="return confirm('Διαγραφή ζώνης {{ $zone->value }};');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">🗑 Διαγραφή</button>
                                    </form>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-muted">Δεν υπάρχουν ζώνες καταχωρημένες.</p>
        @endif
    </div>
@endsection
