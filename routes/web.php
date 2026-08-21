<?php

use App\Http\Controllers\Profil\ProfilController;
use App\Http\Controllers\Promotion\AdhesionController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('accueil'))->name('accueil');

Route::middleware('auth')->group(function () {
    // Accessibles même sans promotion rattachée
    Route::get('/rejoindre', [AdhesionController::class, 'create'])->name('promotion.rejoindre');
    Route::post('/rejoindre', [AdhesionController::class, 'store'])->name('promotion.adherer');
    Route::get('/profil', [ProfilController::class, 'show'])->name('profil.show');

    // Toutes les routes suivantes exigent une promotion active
    Route::middleware('promotion')->group(function () {
        // Les routes métier (publications, entraide, etc.) viendront ici
        // dans les phases suivantes
    });
});