<!-- resources/views/admin/dashboard.blade.php -->
@extends('layouts.pda')
@section('content')
@extends('layouts.pda')

@section('content')
    <h2>Στατιστικά Εβδομάδας</h2>

    <canvas id="salesChart" height="100"></canvas>
    <form method="GET" action="{{ route('statistics.byPeriod') }}">
        Από: <input type="date" name="start_date" value="{{ request('start_date', $startDate->format('Y-m-d')) }}">
        Έως: <input type="date" name="end_date" value="{{ request('end_date', $endDate->format('Y-m-d')) }}">
        <button type="submit">Φιλτράρισμα</button>
    </form>

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
                    <td>{{ number_format($stat->total_revenue, 2) }}</td>
                    <td>{{ \Carbon\Carbon::parse($stat->date)->format('d-m-Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Φόρτωση Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const ctx = document.getElementById('salesChart').getContext('2d');

        new Chart(ctx, {
            type: 'bar', // μπορεί να γίνει και 'line', 'pie', κ.λπ.
            data: {
                labels: @json($labels),
                datasets: [{
                    label: 'Πωλήσεις',
                    data: @json($sales),
                    borderWidth: 1,
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)'
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0 // Για να μην βάζει δεκαδικά
                        }
                    }
                }
            }
        });
    </script>
@endsection
@endsection
