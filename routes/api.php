<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\verificationController;
use App\Http\Controllers\api\creer_ticketController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('/login', [AuthController::class,'login']);
    Route::get('/profil', [AuthController::class,'profil']);
    Route::post('/logout', [AuthController::class,'logout']);
    Route::get('/tirage', [AuthController::class,'tirage']);
   // Route::post('/ticket/creer', [AuthController::class,'creer_ticket']);
    Route::post('/ticket/creer', [creer_ticketController::class,'creer_ticket']);
   // Route::post('/ticket/copier', [AuthController::class,'creer_ticket']);
   
});