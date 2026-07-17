<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MapController;
use App\Http\Controllers\PlaceController;

Route::middleware('throttle:api')->group(function () {
    Route::get('/places', [MapController::class, 'apiPlaces']);
    Route::get('/map-points', [PlaceController::class, 'index']);
    Route::get('/categories', [MapController::class, 'apiCategories']);
});
