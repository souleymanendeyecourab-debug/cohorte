<?php

use App\Http\Controllers\Feed\PublicationController;
use App\Http\Controllers\Profil\ProfilController;
use App\Http\Controllers\Promotion\AdhesionController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('accueil'))->name('accueil');

Route::middleware('auth')->group(function () {
    Route::get('/rejoindre', [AdhesionController::class, 'create'])->name('promotion.rejoindre');
    Route::post('/rejoindre', [AdhesionController::class, 'store'])->name('promotion.adherer');
    Route::get('/profil', [ProfilController::class, 'show'])->name('profil.show');

    Route::middleware('promotion')->group(function () {
        Route::resource('publications', PublicationController::class)
            ->only(['index', 'create', 'store', 'show', 'destroy']);
    });
});