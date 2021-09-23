<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CarteraController;
use App\Http\Controllers\HistorialController;

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
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// auth
Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login',  [UserController::class, 'login']);
    Route::post('register', [UserController::class, 'register']);

    Route::group([
      'middleware' => 'auth:api'
    ], function() {
        Route::get('logout', [UserController::class, 'logout']);
        Route::get('user', [UserController::class, 'user']);
    });
});

// cartera
Route::group([
    'prefix' => 'cartera',
    'middleware' => 'auth:api'
],
    function(){
        // obtener todas las carteras de un usuario
        Route::get('/get/{id}', [CarteraController::class, 'getAll']);

        // crear cartera
        Route::post('/create', [CarteraController::class, 'create']);

        // obtener una cartera de un usuario
        Route::get('/{cartera}', [CarteraController::class, 'getOne']);

        // Eliminar una cartera
        Route::delete('/{cartera}',[CarteraController::class, 'delete']);
        
        // editar Cartera
        Route::put('/',[CarteraController::class, 'edit']);
        // depositar en cartera
        Route::put('/deposit',[CarteraController::class, 'deposit']);
        // retirar en cartera
        Route::put('/withdrawal',[CarteraController::class, 'withdrawal']);

    }
);
// Historial
Route::group([
    'prefix' => 'historial',
    'middleware' => 'auth:api'
],
    function(){
        // obtener todas las carteras de un usuario
        Route::get('/{id}', [HistorialController::class, 'getHistorial']);

    }
);
