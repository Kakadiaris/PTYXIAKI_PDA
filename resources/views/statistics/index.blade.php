@extends('layouts.pda')

@section('content')
<table>
    <thead>
        <tr>
            <th>Προϊόν</th>
            <th>Πωλήσεις</th>
            <th>Συνολικός Τζίρος</th>
            <th>Ημερομηνία</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($statistics as $stat)
            <tr>
                <td>{{ $stat->menuItem->name }}</td>
                <td>{{ $stat->sold_count }}</td>
                <td>{{ $stat->total_revenue }}</td>
                <td>{{ \Carbon\Carbon::parse($stat->date)->format('d-m-Y') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
