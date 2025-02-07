<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DailyController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CalendarController;

Route::get('/', [LoginController::class, 'view'])->name('login.view');
Route::post('/auth', [LoginController::class, 'auth'])->name('login.auth');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['admin'])->group(function () {
    Route::get('admin/dashboard', [AdminController::class, 'dash'])->name('admin.dash');

    Route::get('/admin/create', [AdminController::class, 'create'])->name('admin.create');
    Route::post('/admin/store', [AdminController::class, 'store'])->name('admin.store');
    Route::get('/admin_user/list', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/admin/edit/{id}', [AdminController::class, 'edit'])->name('admin.edit');
    Route::post('/admin/update/{id}', [AdminController::class, 'update'])->name('admin.update');

    Route::get('/admin_client/create', [AdminController::class, 'create_client'])->name('admin_client.create');
    Route::post('/admin_client/store', [AdminController::class, 'store_client'])->name('admin_client.store');
    Route::get('/admin_client/list', [AdminController::class, 'index_client'])->name('admin_client.index');
    Route::get('/admin_client/edit/{id}', [AdminController::class, 'edit_client'])->name('admin_client.edit');
    Route::post('/admin_client/update/{id}', [AdminController::class, 'update_client'])->name('admin_client.update');

    Route::get('/area', [AreaController::class, 'create'])->name('area.create');
    Route::post('/area/store', [AreaController::class, 'store'])->name('area.store');
    Route::get('/area/list', [AreaController::class, 'index'])->name('area.index');
    Route::get('/area/edit/{id}', [AreaController::class, 'edit'])->name('area.edit');
    Route::post('/area/update/{id}', [AreaController::class, 'update'])->name('area.update');

    Route::get('admin/profile', [AdminController::class, 'profile'])->name('profile.create');
    Route::post('admin/profile/update', [AdminController::class, 'admin_update_profile'])->name('admin.profile.update');

    Route::get('/unit/create', [AdminController::class, 'create_unit'])->name('unit.create');
    Route::post('/unit/store', [AdminController::class, 'store_unit'])->name('unit.store');
    Route::get('/unit/list', [AdminController::class, 'index_unit'])->name('unit.index');
    Route::get('/unit/edit/{id}', [AdminController::class, 'edit_unit'])->name('unit.edit');
    Route::post('/unit/update/{id}', [AdminController::class, 'update_unit'])->name('unit.update');

    Route::post('/save_daily_unit', [DailyController::class, 'saveDailyUnit'])->name('save.transactions');
    Route::get('/daily_client', [DailyController::class, 'daily_index'])->name('daily.clients');
    Route::get('/fetch-active-clients', [DailyController::class, 'fetchActiveClients'])->name('fetch.active.clients');
    Route::get('fetch-transactions', [DailyController::class, 'fetchTransactions'])->name('fetch.transactions');
    Route::post('daily-unit', [DailyController::class, 'updatedDailyunit'])->name('update.unit');
    Route::get('/total-units', [DailyController::class, 'getTotalUnits'])->name('grandtotal.unit');
    Route::post('delete-unit', [DailyController::class, 'deleteDailyunit'])->name('delete.unit');
    Route::get('/today-total-selling-unit', [DailyController::class, 'getTodayTotalSellingUnit'])->name('today.total.selling.unit');
    Route::get('/this-month-total-selling-unit', [DailyController::class, 'thisMonthTotalSellingUnit'])->name('month.total.unit');
    Route::get('/total-clients', [DailyController::class, 'totalclients'])->name('total.clients');
    Route::get('/milk-sales-graph', [DailyController::class, 'getLast15DaysMilkSales'])->name('milk.sales.graph');
    Route::get('/year-sales-graph', [DailyController::class, 'getLastYearMilkSales'])->name('year.sales.graph');

    Route::get('/calendar', [CalendarController::class, 'calendar'])->name('calendar');
    Route::get('/transaction-unit/{client_id}', [CalendarController::class, 'gettransactionUnit'])->name('transaction.unit');
    Route::post('/transaction/update', [CalendarController::class, 'transactionUpdate'])->name('transaction.update');
    Route::post('/transaction/store', [CalendarController::class, 'transactionStore'])->name('transaction.store');
    Route::get('/monthly-report', [CalendarController::class, 'monthlyReport'])->name('monthly.report');
    Route::get('/fetch-clients', [CalendarController::class, 'fetchClients'])->name('fetch.clients');

    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
    Route::post('/settings/store', [AdminController::class, 'price_store'])->name('settings.store');
    Route::get('/get-price', [AdminController::class, 'fetch_price'])->name('fetch.price');

    Route::get('/payment_receive', [AdminController::class, 'receive_payment'])->name('receive.payment');
    Route::post('/payment/store', [AdminController::class, 'payment_store'])->name('payment.store');
    Route::get('/expense', [AdminController::class, 'expense'])->name('expense');

    Route::get('/payment_report', [AdminController::class, 'payment_report'])->name('payment.report');
    Route::post('/payment_filter', [AdminController::class, 'payment_filter'])->name('payment.filter');
 
});
//update daily unit using ajax at admin side
Route::post('/update-daily-unit', [ClientController::class, 'updateDailyUnit'])->name('update.daily.unit');

Route::middleware(['user'])->group(function () {
    Route::get('user/dashboard', [UserController::class, 'dash'])->name('user.dash');

    Route::get('/client', [ClientController::class, 'create'])->name('client.create');
    Route::post('/client/store', [ClientController::class, 'store'])->name('client.store');
    Route::get('/client/list', [ClientController::class, 'index'])->name('client.index');
    Route::get('/client/edit/{id}', [ClientController::class, 'edit'])->name('client.edit');
    Route::post('/client/update/{id}', [ClientController::class, 'update'])->name('client.update');

    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::post('/profile/update', [UserController::class, 'update_profile'])->name('profile.update');
});


// Route::middleware(['client'])->group(function () {
//     Route::get('client/dashboard', [ClientController::class, 'dash'])->name('client.dash');
// });
