<?php

use App\Http\Controllers\HobbyController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\UserController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', [UserController::class, 'login']);

Route::resource('siswa', SiswaController::class)->middleware('auth:sanctum');
Route::resource('kelas', KelasController::class)->middleware('auth:sanctum');
Route::resource('hobby', HobbyController::class)->middleware('auth:sanctum');
