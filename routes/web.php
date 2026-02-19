<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SnapshotController;

Route::get('/', function () {
    return redirect()->route('posts.index');
});

// Post CRUD routes
Route::resource('posts', PostController::class);

// Snapshot routes
Route::prefix('snapshots')->name('snapshots.')->group(function () {
    Route::get('/', [SnapshotController::class, 'index'])->name('index');
    Route::post('/create', [SnapshotController::class, 'create'])->name('create');
    Route::post('/compare', [SnapshotController::class, 'compare'])->name('compare');
    Route::get('/load/{snapshotName}', [SnapshotController::class, 'load'])->name('load');
    Route::delete('/delete/{snapshotName}', [SnapshotController::class, 'delete'])->name('delete');
    Route::get('/download/{snapshotName}', [SnapshotController::class, 'download'])->name('download');
});