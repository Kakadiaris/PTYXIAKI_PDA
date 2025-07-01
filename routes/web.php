<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperuserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\WaiterController;
use App\Http\Controllers\KitchenController;
use App\Http\Controllers\BarController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return redirect()->route('login'); // Απλό redirect στην login σελίδα
});

// Προστατευμένα routes
Route::middleware(['auth'])->group(function () {

    // Ρόλοι
    Route::middleware(['auth'])->get('/superuser', [SuperuserController::class, 'index'])->name('superuser.home');
    Route::middleware(['auth'])->get('/admin', [AdminController::class, 'index'])->name('admin.home');
    Route::middleware(['auth'])->get('/waiter', [WaiterController::class, 'index'])->name('waiter.home');
    Route::middleware(['auth'])->get('/kitchen', [KitchenController::class, 'index'])->name('kitchen.home');
    Route::middleware(['auth'])->get('/bar', [BarController::class, 'index'])->name('bar.home');

    // Dashboard κοινό (προαιρετικό)
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Προφίλ
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Φόρτωση auth routes
require __DIR__ . '/auth.php';
