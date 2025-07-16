@extends('layouts.pda')
@section('content')
    <div class="container mt-4">
        <h1 class="mb-4">🍽️ Μενού</h1>
        <div class="mb-4 text-end">
            <a href="{{ route('menu.create') }}" class="btn btn-success">
                ➕ Προσθήκη Προϊόντος
            </a>
        </div>
        @if ($items->count())
            <div class="row row-cols-1 row-cols-md-3 g-4">
                @foreach ($items as $item)
                    <div class="col">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">{{ $item->name }}</h5>
                                <p class="card-text">{{ $item->description ?? 'Χωρίς περιγραφή.' }}</p>
                            </div>
                            <div class="card-footer d-flex justify-content-between align-items-center">

                                <span class="fw-bold">{{ number_format($item->price, 2) }} €</span>
                                @if ($item->category)
                                    <span class="badge bg-primary">{{ $item->category }}</span>
                                @endif
                            </div>
                            <form method="POST" action="{{ route('menu.destroy', $item->id) }}"
                                onsubmit="return confirm('Είστε σίγουροι ότι θέλετε να διαγράψετε αυτό το προϊόν;');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Διαγραφή">
                                    🗑️
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="alert alert-info">
                Δεν υπάρχουν διαθέσιμα είδη μενού.
            </div>
        @endif
    </div>
@endsection
