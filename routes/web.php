<?php

use App\Http\Controllers\Entraide\QuestionController;
use App\Http\Controllers\Entraide\ReponseController;
use App\Http\Controllers\Entraide\ReponseRetenueController;
use App\Http\Controllers\Feed\PublicationController;
use App\Http\Controllers\Feed\SignalementController;
use App\Http\Controllers\Profil\ProfilController;
use App\Http\Controllers\Promotion\AdhesionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Feed\EpinglageController;

Route::get('/', fn () => view('accueil'))->name('accueil');

Route::middleware('auth')->group(function () {
    Route::get('/rejoindre', [AdhesionController::class, 'create'])->name('promotion.rejoindre');
    Route::post('/rejoindre', [AdhesionController::class, 'store'])->name('promotion.adherer');
    Route::get('/profil', [ProfilController::class, 'show'])->name('profil.show');

    Route::middleware('promotion')->group(function () {
        Route::resource('publications', PublicationController::class)
            ->only(['index', 'create', 'store', 'show', 'destroy']);

        Route::post('publications/{publication}/signalements', [SignalementController::class, 'store'])
            ->name('publications.signalements.store');

        Route::resource('questions', QuestionController::class)
            ->only(['index', 'create', 'store', 'show']);

        Route::post('questions/{question}/reponses', [ReponseController::class, 'store'])
            ->name('reponses.store');

        Route::delete('reponses/{reponse}', [ReponseController::class, 'destroy'])
            ->name('reponses.destroy');

        Route::post('questions/{question}/reponse-retenue', [ReponseRetenueController::class, 'store'])
            ->name('reponse-retenue.store');
                  Route::post('publications/{publication}/epingler', [EpinglageController::class, 'toggle'])
            ->name('publications.epingler.toggle');  
    });
});