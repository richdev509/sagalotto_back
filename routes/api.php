<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\verificationController;
use App\Http\Controllers\api\ticketController;
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
Route::middleware('CheckBeforeAccess')->group(function () {
    // Routes protected by the middleware
  // Route::post('api/auth/ticket/creer', [creer_ticketController::class,'creer_ticket']);
  // Route::get('api/auth/tirage', [AuthController::class,'tirage']);
});
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('/login', [AuthController::class,'login']);
    Route::get('/update', [AuthController::class,'update']);

    Route::get('/profil', [AuthController::class,'profil']);
    Route::post('/logout', [AuthController::class,'logout']);
    Route::get('/tirage', [AuthController::class,'tirage']);
    Route::get('/tirage/tout', [AuthController::class,'tirage_list']);
    Route::get('/tirage/result', [AuthController::class,'tirage_result']);



    Route::post('/ticket/creer', [ticketController::class,'creer_ticket'])->middleware('filterJson');
    Route::post('/ticket/creer2', [ticketController::class,'creer_ticket2'])->middleware('filterJson');
    Route::get('/ticket/creer_confirm', [ticketController::class,'confirm_ticket'])->middleware('filterJson');

    Route::get('/ticket/list', [ticketController::class,'list_ticket']);
    Route::post('/ticket/cancel', [ticketController::class,'cancel_ticket']);
    Route::get('/ticket/report', [ticketController::class,'report_ticket']);
    Route::post('/ticket/peyer', [ticketController::class,'payer_ticket']);
    Route::post('/ticket/copier', [ticketController::class,'copier_ticket']);






   
});