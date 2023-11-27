<?php

use App\Http\Controllers\ComponentesController;
use App\Http\Controllers\LoteController;
use App\Http\Controllers\testController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['middleware' => ['cors']], function () {


    Route::prefix('lote')->group(function () {
        Route::controller(LoteController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('/{id}', 'show');
            Route::patch('/{loteId}', 'update');
            Route::post('/', 'store');
            Route::delete('/{id}', 'destroy');
        });
    });

    Route::prefix('user')->group(function () {
        Route::controller(UserController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('/{id}', 'show');
            Route::put('/{id}', 'update');
            Route::post('/', 'store');
            Route::delete('/{id}', 'destroy');

            Route::prefix('{userId}/lote')->group(function () {
                Route::get('/', 'userLotes');
                Route::get('/{loteId}', 'userLote');
                Route::put('/{id}', 'update');
                Route::post('/', 'store');
                Route::delete('/{id}', 'destroy');
            });
        });
    });

    Route::prefix('componentes')->group(function () {
        Route::controller(ComponentesController::class)->group(function () {

            Route::get('/', 'index');
            Route::get('/{id}', 'show');
            Route::put('/{id}', 'update');
            Route::post('/', 'store');
            Route::delete('/{id}', 'destroy');
        });
    });

    Route::prefix('clasificacion')->group(function () {
        Route::controller(ComponentesController::class)->group(function () {

            Route::get('/', 'index');
            Route::get('/{id}', 'show');
            Route::put('/{id}', 'update');
            Route::post('/', 'store');
            Route::delete('/{id}', 'destroy');
        });
    });
});
