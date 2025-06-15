<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Project\ProjectController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    #profile page
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    #project page
    Route::prefix('project')->name('project.')->group(function () {
        //project index
        Route::get('/', [ProjectController::class, 'index'])->name('index');
        Route::get('/data', [ProjectController::class, 'data'])->name('data');
        Route::post('/store', [ProjectController::class, 'store'])->name('store');
        Route::delete('/delete/{id}', [ProjectController::class, 'delete'])->name('delete');

        //project detail
        Route::get('/detail/{id}', [ProjectController::class, 'show'])->name('show');
        Route::post('/update/{id}', [ProjectController::class, 'update'])->name('update');
        Route::post('/detail/store/{id}', [ProjectController::class, 'storeDetail'])->name('detail.store');
        Route::get('/project/data', [ProjectController::class, 'projectDetailsData'])->name('detail');
        Route::get('/detail/edit/{id}', [ProjectController::class, 'editDetail'])->name('detail.edit');
        Route::post('/detail/update/{id}', [ProjectController::class, 'updateDetail'])->name('detail.update');
        Route::delete('/detail/delete/{id}', [ProjectController::class, 'deleteDetail'])->name('detail.delete');
    });
});

require __DIR__.'/auth.php';
