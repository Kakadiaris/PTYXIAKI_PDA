@extends('layouts.pda')
@section('content')

<h2>Ορισμός τρόπου πληρωμής</h2>

<form method="POST" action="{{ route('payments.update', $payment) }}">
    @csrf
    @method('PUT')

    <label for="method">Τρόπος Πληρωμής:</label>
    <select name="method" id="method" required>
        <option value="">-- Επιλογή --</option>
        <option value="cash" {{ $payment->method === 'cash' ? 'selected' : '' }}>Μετρητά</option>
        <option value="card" {{ $payment->method === 'card' ? 'selected' : '' }}>Κάρτα</option>
    </select>

    <button type="submit">Ολοκλήρωση</button>


@endsection
