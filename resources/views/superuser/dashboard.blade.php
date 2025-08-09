<!-- resources/views/admin/dashboard.blade.php -->
@extends('layouts.pda')
@section('content')
    <h2>Στατιστικά Εβδομάδας</h2>

    <canvas id="salesChart" height="100"></canvas>

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
