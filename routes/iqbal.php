<?php

use App\Http\Controllers\PositionController;
use Illuminate\Support\Facades\Route;


Route::middleware('auth')->group(function () {
    Route::resource('positions', PositionController::class);
});
