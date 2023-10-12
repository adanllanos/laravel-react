<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\UsuarioController;

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

/* Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
}); */


Route::controller(ProductController::class)->group(function () {
    Route::get('/products', 'index');
    Route::post('/product', 'store');
    Route::get('/product/{id}', 'show');
    Route::put('/product/{id}', 'update');
    Route::delete('/product/{id}', 'destroy');
});

$router->group(['prefix' => 'usuario'], function () use ($router) {
    /* $router->get('/{pageSize}/size', 'UsuarioController@getUsuario');
    $router->post('', 'UsuarioController@createUsuario');
    $router->put('/{usuarioId}', 'UsuarioController@editUsuario');
    $router->delete('/{usuarioId}', 'UsuarioController@deleteUsuario');
 */

    $router->get('/{pageSize}/size', [UsuarioController::class, 'getUsuario']);
    $router->post('', [UsuarioController::class, 'createUsuario']);
    $router->put('/{usuarioId}', [UsuarioController::class, 'editUsuario']);
    $router->delete('/{usuarioId}', [UsuarioController::class, 'deleteUsuario']);
});

$router->group(['prefix' => 'auth/'], function () use ($router) {
    /* $router->post('login', 'AuthController@postLogin'); */
    $router->post('login', [AuthController::class, 'postLogin']);
});

Route::post('/signup', [AuthController::class, 'signup']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);
