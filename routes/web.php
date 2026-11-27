<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DisponibilidadController;
use App\Http\Controllers\HerramientaController;
use App\Http\Controllers\PrestamoController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
})->name('home');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/herramientas/crear', [HerramientaController::class, 'create'])->name('herramientas.create');
    Route::post('/herramientas', [HerramientaController::class, 'store'])->name('herramientas.store');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('/herramientas', [HerramientaController::class, 'index'])
    ->middleware('auth')
    ->name('herramientas.index');

Route::patch('/herramientas/{herramienta}/disponibilidad', DisponibilidadController::class)
    ->middleware('auth')
    ->name('herramientas.disponibilidad');

Route::post('/herramientas/{herramienta}/prestamo', PrestamoController::class)
    ->middleware('auth')
    ->name('herramientas.prestamo');
