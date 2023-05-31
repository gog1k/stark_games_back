<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['prefix' => 'games'], function () {
    Route::get('{id}', [\App\Http\Controllers\GamesController::class, 'getAction'])->where('id', '[0-9]+');
    Route::get('/autocomplete-list/{mask}', [\App\Http\Controllers\GamesController::class, 'autocompleteListAction']);
    Route::post('/rating/{mask}', [\App\Http\Controllers\GamesController::class, 'setRatingAction'])->where('id', '[0-9]+');
});
