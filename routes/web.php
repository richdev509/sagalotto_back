<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\tirageController;
use App\Http\Controllers\updateSwitchController;
use App\Http\Controllers\ajouterLotGagnantController;
use App\Http\Controllers\parametreController;
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

//espas lo

Route::get('ajout-lo',[ajouterLotGagnantController::class,'ajouterlo'])->name('ajoutlo');
Route::post('ajoutelos',[ajouterLotGagnantController::class, 'store'])->name('savelot');
Route::get('lister-lo',[ajouterLotGagnantController::class,'index'])->name('listlo');
Route::get('/block/update-switch', [updateSwitchController::class, 'updateSwitch']);
Route::post('modifierlo',[ajouterLotGagnantController::class,'modifierlo'])->name('modifierlo');
//tirage
Route::get('ajouter-tirage', [tirageController::class,'create']);
Route::post('editertirage', [tirageController::class,'update']);
Route::get('lister-tirage', [tirageController::class,'index']);
//end tirage
Route::get('/contact', function () {
    return view('contactapp');
});

//parametre
Route::get('maryaj-set',[parametreController::class, 'indexmaryaj'])->name('maryajGratis');
Route::post('updatemontantmg',[parametreController::class,'updatePrixMaryajGratis'])->name('updatemontantmg');
Route::post('updatestatutmg',[parametreController::class,'updatestatut']);
Route::get('ajistelo',[parametreController::class, 'ajistelo'])->name('ajisteprilo');
Route::post('ajistelo',[parametreController::class, 'storelopri'])->name('updateprilo');