<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Όλα τα routes προστατεύονται με auth
Route::middleware(['auth'])->group(function () {

    Route::get('/', function () {
        return view('dashboard'); // κοινή αρχική σελίδα μετά το login
    })->name('home');

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Φόρτωση των auth routes (login, register κ.λπ.)
require __DIR__.'/auth.php';
