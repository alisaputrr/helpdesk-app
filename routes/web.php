<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

// Login (hanya untuk tamu)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.process');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Route statis HARUS di atas route berparameter {id}
    Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/create', [TicketController::class, 'create'])->name('tickets.create');
    Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
    Route::get('/tickets/laporan', [TicketController::class, 'report'])->name('tickets.report');
    Route::get('/tickets/trash', [TicketController::class, 'trash'])->name('tickets.trash');

    Route::get('/tickets/{id}', [TicketController::class, 'show'])->whereNumber('id')->name('tickets.show');
    Route::get('/tickets/{id}/edit', [TicketController::class, 'edit'])->whereNumber('id')->name('tickets.edit');
    Route::put('/tickets/{id}', [TicketController::class, 'update'])->whereNumber('id')->name('tickets.update');
    Route::delete('/tickets/{id}', [TicketController::class, 'destroy'])->whereNumber('id')->name('tickets.destroy');
    Route::post('/tickets/{id}/restore', [TicketController::class, 'restore'])->whereNumber('id')->name('tickets.restore');
    Route::delete('/tickets/{id}/force', [TicketController::class, 'forceDelete'])->whereNumber('id')->name('tickets.force');
});
