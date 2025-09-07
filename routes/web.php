<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\MapaClienteController;
use App\Http\Controllers\Reportes\ReportestockController;
Route::get('/', function () {
    return view('welcome');
});
Route::get('/reportestock/pdf', [ReportestockController::class, 'generarPdf'])->name('reportestock.pdf');
Auth::routes();
Route::get('/clientes', [MapaClienteController::class, 'index'])->name('clientes.index');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/clientes/mapa', [MapaClienteController::class, 'mostrar'])->name('clientes.mapa');
Route::get('/cliente/registrar', [MapaClienteController::class, 'mostrarFormularioMapa'])->name('cliente.registrar');
Route::post('/cliente/registrar', [MapaClienteController::class, 'store'])->name('clientes.store');
Route::get('/clientes/{id}/mapa', [MapaClienteController::class, 'showMapClient'])->name('clientes.map');
Route::middleware('auth')->group(function () {
    Route::view('about', 'about')->name('about');

    Route::get('users', [\App\Http\Controllers\UserController::class, 'index'])->name('users.index');

    Route::get('profile', [\App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::put('profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
});
