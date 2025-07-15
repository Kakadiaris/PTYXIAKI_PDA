@extends('layouts.pda')

@section('content')
<form method="POST" action="{{ route('zones.store') }}">
    @csrf
    <label for="value">Όνομα Ζώνης:</label>
    <input type="text" name="value" required>
    <button type="submit">Δημιουργία</button>
</form>
@endsection
