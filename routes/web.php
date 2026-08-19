<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('accueil'))->name('accueil');

Route::middleware('auth')->group(function () {
    // Les routes métier (publications, entraide, etc.) viendront ici
    // dans les phases suivantes
});