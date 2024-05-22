<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\tirageController;
use App\Http\Controllers\updateSwitchController;
use App\Http\Controllers\ajouterLotGagnantController;
use App\Http\Controllers\parametreController;
use App\Http\Controllers\rapportController;
use App\Http\Controllers\ticketController;
use App\http\Controllers\statistiqueController;
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
})->name('login2');

Route::get('/contact', function () {
    return view('contactapp');
});
Route::get('/register', function () {
    return view('register.register');
});

Route::post('login', [CompanyController::class,'login']);

Route::get('logout', [CompanyController::class,'logout']);
//vendeur



Route::middleware(['web', 'verify.session'])->group(function () {
    // Vos routes nécessitant une vérification de session vont ici


Route::get('admin', [CompanyController::class,'admin']);
Route::get('profil', [CompanyController::class,'profil']);

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
Route::post('/block/update-switch', [updateSwitchController::class, 'updateSwitch']);
Route::post('modifierlo',[ajouterLotGagnantController::class,'modifierlo'])->name('modifierlo');
//tirage
Route::post('ajouterTirage', [tirageController::class,'store']);
Route::get('ajouter-tirage', [tirageController::class,'create']);

Route::post('editertirage', [tirageController::class,'update']);
Route::get('lister-tirage', [tirageController::class,'index']);
//end tirage

//raport
Route::get('rapport', [rapportController::class,'create_rapport']);
Route::get('/raport2', [rapportController::class, 'create_rapport2']);
Route::post('/raport2_get_amount', [rapportController::class, 'get_control']);


//end rapport

//ticket
Route::get('lister-ticket', [ticketController::class,'index']);
Route::get('delete-ticket', [ticketController::class,'destroy']);

//end ticket
//boule
Route::get('boule-show', [ticketController::class,'show_boule']);


//end boule


//parametre
Route::get('maryaj-set',[parametreController::class, 'indexmaryaj'])->name('maryajGratis');
Route::post('updatemontantmg',[parametreController::class,'updatePrixMaryajGratis'])->name('updatemontantmg');
Route::post('updatestatutmg',[parametreController::class,'updatestatut']);
Route::get('ajistelo',[parametreController::class, 'ajistelo'])->name('ajisteprilo');
Route::post('ajistelo',[parametreController::class, 'storelopri'])->name('updateprilo');
Route::get('lotconfig',[parametreController::class, 'create_config'])->name('lotconfig');
Route::post('editerdelai',[parametreController::class, 'update_delai']);
Route::get('limitprix',[parametreController::class, 'limitprixview'])->name('limitprix');
Route::post('limitprixstore',[parametreController::class, 'limitprixstore'])->name('limitprixstore');
Route::Post('editpassword', [CompanyController::class,'new_password']);




Route::get('ajisteprix',[parametreController::class, 'ajoutlimitprixboulView']);
Route::post('ajisteprix',[parametreController::class, 'saveprixlimit'])->name('saveprixlimit');
Route::post('m-l-p',[parametreController::class, 'modifierLimitePrix'])->name('modifierLimitePrix');


Route::get('/plan', [parametreController::class, 'viewinfo']) ;
Route::post('/up-g', [parametreController::class, 'update_general'])->name('up-g');

//statistique
Route::post('getstatistiqueSimple', [statistiqueController::class,'getstatistiqueSimple'])->name('getstatistiqueSimple');
Route::get('stat', [statistiqueController::class,'view']);


});

