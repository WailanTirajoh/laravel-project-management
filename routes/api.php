<?php

use App\Http\Controllers\Api\ApiStatusColorController;
use App\Http\Controllers\Api\ApiStatusController;
use App\Http\Controllers\Api\ApiTaskController;
use App\Http\Controllers\Api\ApiTaskSortController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::put('/statuses/{status}/color', [ApiStatusColorController::class, 'update'])->name('statuses.color.update');
    Route::put('/tasks/sort', [ApiTaskSortController::class, 'update'])->name('tasks.sort.update');
    Route::resource('tasks', ApiTaskController::class);
});

// route('tas');
