@extends('layouts.pda')
@section('content')
    @php
        // Ομαδοποίηση προϊόντων ανά κατηγορία
        $groupedItems = $items->groupBy('category');

        // Map για να δείχνουμε ελληνικά labels
        $categoryLabels = [
            'cofee' => 'Καφές',
            'snack' => 'Σνακ',
            'drinks' => 'Ποτά',
            'ximos' => 'Αναψυκτικα - Χυμοι',
        ];
    @endphp
    <div class="container mt-4">
        <h1 class="mb-4">Μενού</h1>
        <div class="mb-4 text-end">
            <a href="{{ route('menu.create') }}" class="btn btn-primary new-order-btn">
                Προσθήκη Προϊόντος
            </a>
        </div>

        @forelse ($groupedItems as $category => $products)
            <h3 class="mt-4">{{ $categoryLabels[$category] ?? ucfirst($category) }}</h3>
            <div class="row row-cols-1 row-cols-md-3 g-4">
                @foreach ($products as $item)
                    <div class="col">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">{{ $item->name }}</h5>
                                <p class="card-text">{{ $item->description ?? 'Χωρίς περιγραφή.' }}</p>
                            </div>
                            <div class="card-footer d-flex justify-content-between align-items-center">
                                <span class="fw-bold">{{ number_format($item->price, 2) }} €</span>
                                <span
                                    class="badge bg-primary">{{ $categoryLabels[$item->category] ?? $item->category }}</span>
                            </div>
                            <form method="POST" action="{{ route('menu.destroy', $item->id) }}"
                                onsubmit="return confirm('Είστε σίγουροι ότι θέλετε να διαγράψετε αυτό το προϊόν;');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Διαγραφή">
                                    Διαγραφή
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @empty
            <div class="alert alert-info">
                Δεν υπάρχουν διαθέσιμα είδη μενού.
            </div>
        @endforelse
    </div>
@endsection
