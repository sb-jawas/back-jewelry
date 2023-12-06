<?php


use App\Http\Controllers\ComponentesController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Lotes\ClasificadorController;
use App\Http\Controllers\Lotes\ColaboradorController;
use App\Http\Controllers\Lotes\LoteController;


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

Route::group(['middleware' => ['cors']], function () {


    Route::get('empresa/lote/{loteId}', [ClasificadorController::class, 'infoDespiece']);
    Route::get('empresa/mis-lotes/{userId}', [LoteController::class, 'index']);
    Route::put('empresa/mis-lotes/{userId}/cancelar', [LoteController::class, 'cancelar']);

    Route::prefix('colaborador')->group(function () {
        Route::controller(ColaboradorController::class)->group(function () {
            Route::get('{userId}/mis-lotes', 'index');
            Route::get('{userId}/lote/{loteId}', 'show');
            Route::post('lote', 'store');
            Route::patch('lote/{loteId}', 'rechazar');
        });
    });

    Route::prefix('clasificador')->group(function () {
        Route::controller(ClasificadorController::class)->group(function () {
            Route::get('/disponibles', 'disponible');
            Route::get('/{loteId}', 'show');
            Route::get('/{loteId}/rechazar', 'rechazar');
            Route::post('{loteId}/clasificar', 'clasificar');
            Route::patch('/{loteId}', 'update');
            Route::post('/', 'store');
        });
    });

    Route::prefix('lote')->group(function () {
        Route::controller(LoteController::class)->group(function () {
            Route::get('/disponibles', 'disponible');
            Route::get('/{loteId}', 'show');
            Route::get('/{loteId}/rechazar', 'rechazar');
            Route::post('{loteId}/clasificar', 'clasificar');
            Route::patch('/{loteId}', 'update');
            Route::post('/', 'store');
        });
    });

    Route::prefix('user')->group(function () {
        Route::controller(ClasificadorController::class)->group(function () {
            Route::post('{userId}/asign', 'store');
        });


        Route::controller(ClasificadorController::class)->group(function () {
            Route::get('/{id}/lotes', 'show');
            Route::post('/lote', 'store');
        });

        Route::controller(ComponentesController::class)->group(function () {
            Route::get('{userId}/componentes', 'index');
            Route::get('{userId}/componentes/{id}', 'show');
            Route::put('{userId}/componentes/{id}', 'update');
            Route::post('componete', 'store');
            Route::delete('{userId}/componentes/{id}', 'destroy');
        });

        Route::post('/login', [AuthController::class, 'login']);

        Route::post('/register', [AuthController::class, 'register']);
        Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
            return $request->user();
        });
        Route::controller(UserController::class)->group(function () {
            Route::get('/{id}/mis-roles', 'roles');
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

    Route::controller(ComponentesController::class)->group(function () {
        Route::get('/componentes', 'index');
        Route::get('/componentes/{id}', 'show');
        Route::put('/componentes/{id}', 'update');
        Route::post('componete', 'store');
        Route::delete('/componentes/{id}', 'destroy');
    });
});


    // Route::post('/users', [UserController::class, 'store']);
    // Route::prefix('user/lote')->group(function () {
    //     Route::controller(LoteUserController::class)->group(function () {
    //         Route::get('/', 'index');
    //         Route::get('/{id}', 'show');
    //         Route::patch('/{loteId}', 'update');
    //         Route::post('/', 'store');
    //         Route::delete('/{id}', 'destroy');
    //     });
    // });
// });
