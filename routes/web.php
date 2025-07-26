<?php
use App\Http\Controllers\MenuItemController;
use App\Http\Controllers\ZoneController;

use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperuserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\WaiterController;
use App\Http\Controllers\KitchenController;
use App\Http\Controllers\BarController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\StatisticController;


use App\Models\Table;


Route::get('/', function () {
    return redirect()->route('login'); // Απλό redirect στην login σελίδα
});
Route::middleware(['auth'])->group(function () {
    Route::get('/tables', [TableController::class, 'index'])->name('tables.view');
    Route::get('/tables/create', [TableController::class, 'create'])->name('tables.create');
    Route::post('/tables', [TableController::class, 'store'])->name('tables.store');
    Route::delete('/tables/{table}', [TableController::class, 'destroy'])->name('tables.destroy');

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
    Route::post('/orders/{order}/complete', [\App\Http\Controllers\OrderController::class, 'complete'])->name('orders.complete');
    Route::delete('/orders/{order}', [\App\Http\Controllers\OrderController::class, 'destroy'])->name('orders.destroy');



});
Route::middleware(['auth'])->group(function (){
    Route::get('/menu',[MenuItemController::class, 'index'])->name('menu.index');
});
Route::middleware(['auth'])->group(function () {
    Route::get('/menu/create', [MenuItemController::class, 'create'])->name('menu.create');
    Route::post('/menu', [MenuItemController::class, 'store'])->name('menu.store');
    Route::delete('/menu/{menu}', [MenuItemController::class, 'destroy'])->name('menu.destroy');

});



//Routes για πληρωμες 
Route::get('/payments/{payment}/edit', [PaymentController::class, 'edit'])->name('payments.edit');
Route::put('/payments/{payment}', [PaymentController::class, 'update'])->name('payments.update');


//Routes για δημιουργία zones
Route::get('/zones/create', [ZoneController::class, 'create'])->name('zones.create');
Route::post('/zones', [ZoneController::class, 'store'])->name('zones.store');
Route::get('/zones', [ZoneController::class, 'index'])->name('zones.index');
Route::delete('/zones/{zone}', [ZoneController::class, 'destroy'])->name('zones.destroy');
Route::get('/zones/{zone}/tables', [TableController::class, 'byZone'])->name('tables.byZone');




//Routes για στατιστικα
Route::get('/statistics/week', [StatisticController::class, 'showCurrentWeekStatistics']);
Route::get('/statistics/month', [StatisticController::class, 'showCurrentMonthStatistics']);
Route::get('/statistics/previous-month', [StatisticController::class, 'showPreviousMonthStatistics']);
Route::get('/statistics/date', [StatisticController::class, 'showStatisticsByDate']);
Route::get('/statistics/period', [StatisticController::class, 'showStatisticsByPeriod']);

// Φόρτωση auth routes
require __DIR__ . '/auth.php';
