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

//Route::middleware(['auth:api', 'scopes:admin'])->group(function () {
    Route::get('/all', [\App\Http\Controllers\Controller::class, 'helloAction']);


    Route::group(['prefix' => 'room-items'], function () {
        Route::get('/', [\App\Http\Controllers\Admin\RoomItemController::class, 'indexAction']);
        Route::post('/create', [\App\Http\Controllers\Admin\RoomItemController::class, 'createAction']);
        Route::post('/update', [\App\Http\Controllers\Admin\RoomItemController::class, 'updateAction']);
    });

    Route::group(['prefix' => 'room-item-template'], function () {
        Route::get('/', [\App\Http\Controllers\Admin\ItemTemplateController::class, 'indexAction']);
        Route::get('{id}', [\App\Http\Controllers\Admin\ItemTemplateController::class, 'getAction']);
        Route::get('/template/{templateId}', [\App\Http\Controllers\Admin\ItemTemplateController::class, 'listForTemplateAction']);
        Route::get('/item/{itemId}', [\App\Http\Controllers\Admin\ItemTemplateController::class, 'listForItemAction']);
        Route::post('/create', [\App\Http\Controllers\Admin\ItemTemplateController::class, 'createAction']);
        Route::post('/update', [\App\Http\Controllers\Admin\ItemTemplateController::class, 'updateAction']);
    });
//});
