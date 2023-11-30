<?php

use App\Http\Controllers\ComponentesController;
use App\Http\Controllers\LoteController;
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

Route::get('/test',[testController::class,'index']);

Route::get('/user/',[UserController::class,'index']);
Route::get('/user/{id}',[UserController::class,'show']);
Route::get('/lote/',[LoteController::class,'index']);
Route::get('/lote/{userId}',[LoteController::class,'show']);
Route::patch('/lote/{loteId}',[LoteController::class,'update']);
Route::post('/lote',[LoteController::class,'store']);
Route::post('/user/lote',[LoteController::class,'asignlote']);

Route::get('/componentes',[ComponentesController::class,'index']);
Route::post('/login', [AuthController::class, 'login']);

Route::post('/register', [AuthController::class, 'register']);