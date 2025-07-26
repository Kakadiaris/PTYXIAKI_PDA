@extends('layouts.pda')

@section('content')
    <a href="{{ route('tables.create') }}" class="btn btn-primary mb-4">➕ Νέο Τραπέζι</a>

    <div class="container text-center">
        <h2 class="mb-4">Τραπέζια για Ζώνη {{ $zone->value }}</h2>

        <div class="row justify-content-center">
            @foreach ($tables as $table)
                <div class="col-6 col-md-3 mb-3 position-relative">

                    {{-- Κουμπί τραπεζιού --}}
                    <a href="#" class="btn text-white w-100 py-4"
                        style="font-size: 22px; border-radius: 16px;
                          background-color:
                            {{ $table->status === 'free' ? '#28a745' : ($table->status === 'pending' ? '#ffc107' : '#dc3545') }};">
                        {{ $table->zone->value }}{{ $table->number }}
                    </a>

                    {{-- Κουμπί διαγραφής --}}
                    <form action="{{ route('tables.destroy', $table->id) }}" method="POST"
                        onsubmit="return confirm('Είσαι σίγουρος ότι θέλεις να διαγράψεις το τραπέζι {{ $table->zone->value }}{{ $table->number }};')"
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

        <a href="{{ route('tables.view') }}" class="btn btn-secondary mt-4">⬅ Επιστροφή σε όλα τα τραπέζια</a>
    </div>
@endsection
