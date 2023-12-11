<?php

use App\Http\Controllers\API\AuthController as APIAuthController;
use App\Http\Controllers\ComponentesController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Lotes\ClasificadorController;
use App\Http\Controllers\Lotes\ColaboradorController;
use App\Http\Controllers\Lotes\LoteController;
use App\Http\Controllers\User\UserController as UserUserController;

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

    Route::controller(APIAuthController::class)->group(function () {
        Route::post('signup', 'signup');
        Route::post('login', 'login');
        Route::post('logout/{userId}', 'logout');
        Route::post('full-logout/{userId}', 'fullLogout');
    });
    Route::post('signup/image', [UserUserController::class,'uploadImage']);
    Route::post('forget-pass', [UserUserController::class,'forgetPass']);

    Route::get('', function () {
        return response()->json("Unauthorized", 401);
    })->name('nologin');


    Route::middleware('auth:sanctum')->group(function () {

        Route::get('info-despiece/{loteId}', [ClasificadorController::class, 'infoDespiece']);

        Route::prefix('colaborador')->middleware('colaborador')->group(function () {
            Route::controller(ColaboradorController::class)->group(function () {
                Route::get('{userId}/mis-lotes', 'index');
                Route::get('{userId}/lote/{loteId}', 'show');
                Route::post('lote', 'store');
                Route::patch('lote/{loteId}', 'cancelar');
            });
        });

        Route::prefix('clasificador')->middleware('clasificador')->group(function () {
            Route::controller(ClasificadorController::class)->group(function () {
                Route::get('lotes', 'todos');
                Route::get('disponibles', 'disponible');
                Route::get('{userId}/mis-lotes', 'index');
                Route::get('{userId}/mis-clasificados', 'clasificados');
                Route::get('lote/{loteId}', 'show');
                Route::post('{userId}/asign', 'store');
                Route::patch('{loteId}/rechazar', 'rechazar');
                Route::put('{loteId}/clasificar', 'clasificar');
            });

            Route::controller(ComponentesController::class)->group(function () {
                Route::get('{userId}/componentes', 'index');
                Route::get('{userId}/componentes/{componenteId}', 'showByUser');
            });
        });

        Route::controller(ComponentesController::class)->middleware('designer')->group(function () {
            Route::prefix("componentes")->group(function () {
                Route::get('/', 'allComponentes');
                Route::get('{componenteId}', 'show');
                Route::patch('{componenteId}', 'update');
                Route::post('', 'store');
                Route::delete('{componenteId}', 'destroy');
            });
        });

        Route::prefix('lote')->group(function () {
            Route::controller(LoteController::class)->group(function () {
                Route::get('/{loteId}', 'show');
                Route::post('/', 'store');
            });
        });


        // Route::post('/login', [AuthController::class, 'login']);

        // Route::post('/register', [AuthController::class, 'register']);
        // Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        //     return $request->user();
        // });
        
        Route::controller(UserUserController::class)->group(function () {
            Route::prefix('admin')->middleware('admin')->group(function () {
                Route::get('/', 'index');
                Route::get('/{userId}', 'show');
                Route::get('/{userId}/activate-account', 'activeUser');
                Route::post('/search', 'searchUserByEmail');
                Route::post('', 'store');
                Route::put('/{userId}/program-desactivate', 'programBaja');
                Route::put('/{userId}/desactivate-account', 'darBaja');
                Route::put('/{userId}', 'update');
                Route::put('/{userId}/roles', 'updateRoles');
                Route::delete('/{userId}', 'destroy');
            });
            Route::prefix('user')->group(function () {
                Route::get('/{id}/mis-roles', 'roles');
                Route::get('/{userId}', 'show');
                Route::put('/{userId}', 'update');
                Route::post('/', 'store');
                Route::post('{userId}/image', 'updateImage');
                Route::delete('/{userId}', 'destroy');

                Route::prefix('{userId}/lote')->group(function () {
                    Route::controller(LoteController::class)->group(function () {
                        Route::get('/', 'show');
                    });
                });
            });

            Route::post('/image','uploadImage');
        });
    });
});
