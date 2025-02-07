<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

// Route::middleware('auth:api')->group(function () {
    Route::post('/admin/store', [ApiController::class, 'store'])->name('admin.store');
    Route::post('/admin/update', [ApiController::class, 'update'])->name('admin.update');
// });

Route::get('logins', [ApiController::class, 'index']);
Route::post('logins', [ApiController::class, 'store_logins']);
// Route::post('logins/update', [ApiController::class, 'update_logins']);
