@extends('layouts.pda')

@section('content')
<a href="{{ route('tables.create') }}" class="btn btn-primary mb-4">➕ Νέο Τραπέζι</a>

<div class="container text-center">
    <h2 class="mb-4">Τραπέζια</h2>

    <div class="row justify-content-center">
        
        @foreach ($tables as $table)
            <div class="col-6 col-md-3 mb-3">
                <a href="#"
                   class="btn text-white w-100 py-4"
                   style="font-size: 22px; border-radius: 16px;
                          background-color:
                            {{ $table->status === 'free' ? '#28a745' : 
                               ($table->status === 'pending' ? '#ffc107' : '#dc3545') }}">
                    {{ $table->zone }}{{ $table->number }}
                </a>
            </div>
        @endforeach
    </div>
</div>
@endsection
