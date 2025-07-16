@extends('layouts.pda')

@section('content')
    <div class="container">
        <h2>Προσθήκη Προϊόντος</h2>

        <form method="POST" action="{{ route('menu.store') }}">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">Όνομα</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="price" class="form-label">Τιμή (€)</label>
                <input type="number" step="0.01" name="price" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="target" class="form-label">Τμήμα</label>
                <select name="target" class="form-select" required>
                    <option value="">-- Επιλογή --</option>
                    <option value="kitchen">Κουζίνα</option>
                    <option value="bar">Bar</option>
                </select>
            </div>
             <div class="mb-3">
                <label for="category" class="form-label">Κατηγορία</label>
                <select name="category" class="form-select" required>
                    <option value="">-- Επιλογή Κατηγορίας --</option>
                    <option value="snack">Snack</option>
                    <option value="coffee">Καφές</option>
                    <option value="drinks">Ποτά</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Αποθήκευση</button>
        </form>
    </div>
@endsection
