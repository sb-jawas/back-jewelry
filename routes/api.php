<?php

use App\Http\Controllers\ClasificadorController;
use App\Http\Controllers\ComponentesController;
use App\Http\Controllers\LoteController;
use App\Http\Controllers\LoteUserController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Models\LoteUser;

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


    Route::get('empresa/mis-lotes/{userId}', [LoteController::class,'index']);
    Route::get('colaborador/mis-lotes/{userId}', [ClasificadorController::class,'index']);
    Route::get('empresa/lote/{loteId}', [ClasificadorController::class,'infoDespiece']);
    
        /**
         * @author: badr
         * Rutas para CRUD lote.
         * */
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

        //

        /**
         * @author: badr
         * Rutas que un usuario puede realizar.
         * */
        Route::controller(ClasificadorController::class)->group(function () {
            Route::get('/{id}/lotes', 'show');
            Route::post('/lote', 'store');
        });
        /**
         * @author: badr
         * Rutas que un usuario puede hacer con componentes.
         * */
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
