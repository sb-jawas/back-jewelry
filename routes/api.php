<?php

use App\Http\Controllers\ComponentesController;
use App\Http\Controllers\LoteController;
use App\Http\Controllers\LoteUserController;
use App\Http\Controllers\testController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

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

    Route::controller(LoteController::class)->group(function () {
        Route::get('/mis-lotes/{userId}', 'index');
    });
    Route::prefix('lote')->group(function () {
        Route::controller(LoteController::class)->group(function () {
            Route::get('/disponibles', 'disponible');
            Route::get('/{id}', 'show');
            Route::patch('/{loteId}', 'update');
            Route::post('/', 'store');
            Route::post('/clasificador', 'clasficado');
            Route::post('/lote', 'asign');
            Route::delete('/{id}', 'destroy');
        });
    });

Route::get('/componentes',[ComponentesController::class,'index']);
Route::post('/login', [AuthController::class, 'login']);

Route::post('/register', [AuthController::class, 'register']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/users', [UserController::class, 'store']);
    Route::prefix('user/lote')->group(function () {
        Route::controller(LoteUserController::class)->group(function () {
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
                Route::controller(LoteController::class)->group(function () {
                    Route::get('/', 'show');
                });
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
