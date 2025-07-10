<?php
use App\Http\Controllers\MenuItemController;

use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperuserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\WaiterController;
use App\Http\Controllers\KitchenController;
use App\Http\Controllers\BarController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TableController;

use App\Models\Table;


Route::get('/', function () {
    return redirect()->route('login'); // Απλό redirect στην login σελίδα
});
Route::middleware(['auth'])->group(function () {
    Route::get('/tables', [TableController::class, 'index'])->name('tables.view');
    Route::get('/tables/create', [TableController::class, 'create'])->name('tables.create');
    Route::post('/tables', [TableController::class, 'store'])->name('tables.store');
});




// Προστατευμένα routes
Route::middleware(['auth'])->group(function () {

    // Ρόλοι
    Route::middleware(['auth', 'role:superuser'])->get('/superuser', [SuperuserController::class, 'index'])->name('superuser.home');
    Route::middleware(['auth', 'role:admin'])->get('/admin', [AdminController::class, 'index'])->name('admin.home');
    Route::middleware(['auth', 'role:waiter'])->get('/waiter', [WaiterController::class, 'index'])->name('waiter.home');
    Route::middleware(['auth', 'role:kitchen'])->get('/kitchen', [KitchenController::class, 'index'])->name('kitchen.home');
    Route::middleware(['auth', 'role:bar'])->get('/bar', [BarController::class, 'index'])->name('bar.home');

    // Dashboard κοινό (προαιρετικό)
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Προφίλ
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Routes για παραγγελίες
    Route::middleware(['auth'])->group(function () {
        Route::resource('orders', OrderController::class);
    });

});
Route::middleware(['auth'])->group(function (){
    Route::get('/menu',[MenuItemController::class, 'index'])->name('menu.index');
});

// Φόρτωση auth routes
require __DIR__ . '/auth.php';
