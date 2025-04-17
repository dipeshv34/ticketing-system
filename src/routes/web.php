<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TicketChatController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    //authentication
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //SuperAdmin
    Route::middleware(\App\Http\Middleware\EnsureRole::class.':super_admin')
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            // CRUD on clients
            Route::resource('clients', ClientController::class);
        });

    //Client & Admin
    Route::middleware(\App\Http\Middleware\TenantScopeMiddleware::class)
        ->prefix('tickets')
        ->name('tickets.')
        ->group(function () {
            // List & create tickets
            Route::get('/',   [TicketController::class, 'index'])->name('index');
            Route::post('/',  [TicketController::class, 'store'])->name('store');

            // Show single ticket & update status/assignee
            Route::get('{ticket}', [TicketController::class, 'show'])->name('show');
            Route::patch('{ticket}', [TicketController::class, 'update'])->name('update');

            // Add a reply to a ticket
            Route::post('{ticket}/replies', [TicketChatController::class, 'store'])
                ->name('replies.store');
        });
});

require __DIR__.'/auth.php';
