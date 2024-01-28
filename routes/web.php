<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\tirageController;
use App\Http\Controllers\updateSwitchController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/sagacetech', function () {
    return view('saga');
});

Route::get('/contactsaga', function () {
    return view('contactsaga');
});

Route::get('/login', function () {
    
    return view('login');
});


Route::post('login', [CompanyController::class,'login']);

Route::get('admin', [CompanyController::class,'admin']);

Route::get('profil', [CompanyController::class,'profil']);

Route::get('logout', [CompanyController::class,'logout']);
//vendeur

Route::get('ajouter-vendeur', [CompanyController::class,'create_vendeur']);

Route::post('ajouterVendeur', [CompanyController::class,'store_vendeur']);

Route::get('lister-vendeur', [CompanyController::class,'index_vendeur']);

Route::get('editer-vendeur', [CompanyController::class,'edit_vendeur']);

Route::post('editervendeur', [CompanyController::class,'update_vendeur']);
//end vendeur
Route::get('/block',[updateSwitchController::class, 'index']);


Route::get('/block/update-switch', [updateSwitchController::class, 'updateSwitch']);

//tirage
Route::get('ajouter-tirage', [tirageController::class,'create']);

Route::post('editertirage', [tirageController::class,'update']);


Route::get('lister-tirage', [tirageController::class,'index']);
//end tirage
Route::get('/contact', function () {
    return view('contactapp');
});

