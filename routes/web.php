<?php

use App\Http\Controllers\TutorialController;

Route::get('/', [TutorialController::class, 'index']);
Route::get('/tutorials/{tutorial}', [TutorialController::class, 'show'])->name('tutorials.show');
Route::get('/search', [TutorialController::class, 'search'])->name('tutorials.search');