<?php
use Tymon\JWTAuth\Providers\Auth\Illuminate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\tirageController;
use App\Http\Controllers\updateSwitchController;
use App\Http\Controllers\ajouterLotGagnantController;
use App\Http\Controllers\parametreController;
use App\Http\Controllers\rapportController;
use App\Http\Controllers\ticketController;
use App\Http\Controllers\statistiqueController;
use App\Http\Controllers\superadmin\SystemController;
use Illuminate\Http\Request;
use App\Http\Controllers\executeTirageAuto;
use App\Http\Controllers\historiquetransaction;
use App\Http\Controllers\statistiquegeneralController;
use App\Http\Controllers\superadmin\abonnementController;
use App\Http\Controllers\historiquetransanction;
use App\Http\Controllers\superadmin\testjobcontroller;
use App\Http\Controllers\branchController;
use App\Http\Controllers\superviseur\adminController;

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

Route::get('/testjob', [testjobcontroller::class, 'lancement']);
Route::post('/wp-admin/auth2', [SystemController::class, 'auth2']);

Route::get('wp-admin/login', function () {
    return view('superadmin.login');
})->name('wplogin');
Route::get('start-app', function () {
    return view('start_app');
})->name('start-app');
//superviseur
Route::post('/superviseur/auth2', [adminController::class, 'login']);

Route::get('superviseur/login', function () {
    return view('superviseur.login');
})->name('suplogin');



//fin
Route::get('/', function () {
    return view('welcome');
});


Route::get('/sagacetech', function () {
    return view('saga');
});

Route::get('/contactsaga', function () {
    return view('contactsaga');
});


Route::middleware(['web', 'HandleExpiredSession'])->group(function () {
    Route::get('/login', function () {
        return view('login');
    })->name('login2');
});
Route::get('/contact', function () {
    return view('contactapp');
});
Route::get('/register', function () {
    return view('register.register');
});

Route::post('login', [CompanyController::class, 'login']);

Route::get('logout', [CompanyController::class, 'logout']);
Route::get('/wp-admin/logout', [SystemController::class, 'logout']);
//vendeur



Route::middleware(['web', 'verify.session'])->group(function () {
    // Vos routes nécessitant une vérification de session vont ici


    Route::get('/load-more', [ajouterLotGagnantController::class, 'loadMore'])->name('load-more');

    Route::get('admin', [CompanyController::class, 'admin']);
    Route::get('profil', [CompanyController::class, 'profil']);

    Route::get('ajouter-vendeur', [CompanyController::class, 'create_vendeur']);

    Route::post('ajouterVendeur', [CompanyController::class, 'store_vendeur']);

    Route::get('lister-vendeur', [CompanyController::class, 'index_vendeur']);

    Route::get('editer-vendeur', [CompanyController::class, 'edit_vendeur']);

    Route::post('editervendeur', [CompanyController::class, 'update_vendeur']);
    //end vendeur
    Route::get('/block', [updateSwitchController::class, 'index']);

    //espas lo

    Route::get('ajout-lo', [ajouterLotGagnantController::class, 'ajouterlo'])->name('ajoutlo');
    Route::post('ajoutelos', [ajouterLotGagnantController::class, 'store'])->name('savelot');
    Route::get('lister-lo', [ajouterLotGagnantController::class, 'index'])->name('listlo');
    Route::post('/block/update-switch', [updateSwitchController::class, 'updateSwitch']);
    Route::post('modifierlo', [ajouterLotGagnantController::class, 'modifierlo'])->name('modifierlo');
    //tirage
    Route::post('ajouterTirage', [tirageController::class, 'store']);
    Route::get('ajouter-tirage', [tirageController::class, 'create']);
    Route::post('getTirageDetails', [tirageController::class, 'getTirage']);

    Route::post('editertirage', [tirageController::class, 'update']);
    Route::get('lister-tirage', [tirageController::class, 'index']);
    //end tirage

    //raport
    Route::get('rapport', [rapportController::class, 'create_rapport']);
    Route::get('control', [rapportController::class, 'create_control']);

    Route::get('/raport2', [rapportController::class, 'create_rapport2']);
    Route::post('/raport2_get_amount', [rapportController::class, 'get_control']);
    Route::post('/get_control_date', [rapportController::class, 'get_control_date']);
    Route::post('/save_reglement', [rapportController::class, 'save_control']);



    //end rapport

    //ticket
    Route::get('lister-ticket', [ticketController::class, 'index']);
    Route::get('lister-ticket-delete', [ticketController::class, 'index_delete']);

    Route::get('delete-ticket', [ticketController::class, 'destroy']);

    //end ticket
    //boules
    Route::get('boule-show', [ticketController::class, 'show_boule']);


    //end boule


    //parametre
    Route::get('maryaj-set', [parametreController::class, 'indexmaryaj'])->name('maryajGratis');
    Route::post('updatemontantmg', [parametreController::class, 'updatePrixMaryajGratis'])->name('updatemontantmg');
    Route::post('updatestatutmg', [parametreController::class, 'updatestatut']);
    Route::get('ajistelo', [parametreController::class, 'ajistelo'])->name('ajisteprilo');
    Route::post('getByBranch', [parametreController::class, 'getPrixLo']);
    Route::post('maryajByBranch', [parametreController::class, 'getPrixMaryaj']);
    Route::post('delete-multiple-limits', [parametreController::class, 'deleteMultiple'])->name('deleteMultipleLimits');
    Route::get('script', [parametreController::class, 'updateBranch']);

  


    Route::post('ajistelo', [parametreController::class, 'storelopri'])->name('updateprilo');
    Route::post('update_prilo_vendeur', [parametreController::class, 'update_prilo_vendeur'])->name('updateprilovendeur');
    Route::get('deleteprilo_vendeur/{id}', [parametreController::class, 'deleteprilo_vendeur'])->name('deleteprilovendeur');
    Route::get('lotconfig', [parametreController::class, 'create_config'])->name('lotconfig');
    Route::get('fich', [parametreController::class, 'config_fich'])->name('fich');
    Route::post('fich_update', [parametreController::class, 'config_fichUpdate'])->name('fichUpdate');


    Route::post('editerdelai', [parametreController::class, 'update_delai']);
    Route::get('limitprix', [parametreController::class, 'limitprixview'])->name('limitprix');
    Route::post('limitprixstore', [parametreController::class, 'limitprixstore'])->name('limitprixstore');
    Route::Post('editpassword', [CompanyController::class, 'new_password']);




    Route::get('ajisteprix', [parametreController::class, 'ajoutlimitprixboulView']);
    Route::post('ajisteprix', [parametreController::class, 'saveprixlimit'])->name('saveprixlimit');
    Route::post('m-l-p', [parametreController::class, 'modifierLimitePrix'])->name('modifierLimitePrix');


    Route::get('/plan', [parametreController::class, 'viewinfo']);
    Route::post('/plan', [parametreController::class, 'viewinfo']);
    Route::post('/up-g', [parametreController::class, 'update_general'])->name('up-g');


    Route::post('getstatistiqueSimple', [statistiqueController::class, 'getstatistiqueSimple'])->name('getstatistiqueSimple');
    Route::get('stat', [statistiqueController::class, 'viewpage']);
    Route::get('/statistiquegeneral', [statistiquegeneralController::class, 'viewpage'])->name('statistique');


    //branch route and superviser route
    Route::get('creer_branch', [branchController::class, 'create_branch']);
    Route::post('creerBranch', [branchController::class, 'store_branch']);
    Route::get('editer_branch', [branchController::class, 'edit_branch']);
    Route::post('editerBranch', [branchController::class, 'update_branch']);
    Route::get('lister_branch', [branchController::class, 'index_branch']);
});



Route::middleware(['web', 'CheckSuperviseur'])->group(function () {

    Route::get('/sup_list-vendeur', [adminController::class, 'index_vendeur']);
    Route::get('/sup_edit-vendeur', [adminController::class, 'edit_vendeur']);
    Route::post('/sup_update-vendeur', [adminController::class, 'update_vendeur']);
    Route::get('/superviseur', [adminController::class, 'admin']);
    Route::get('/sup_rapport2', [adminController::class, 'create_rapport2']);
    Route::get('/sup_rapport', [adminController::class, 'create_rapport']);


  
    
});



Route::middleware(['web', 'chekadmin'])->group(function () {


    Route::get('/wp-admin/admin', [SystemController::class, 'viewadmin']);
    Route::get('/wp-admin/add-compagnie', function () {
        $reference = DB::table('reference')->get();
        return view('superadmin.ajouter_compagnie',['reference'=>$reference]);
    });
    Route::get('/wp-admin/add-vendeur', function (Request $request) {
        $idc = $request->query('idC');
        return view('superadmin.ajouter_vendeur', compact('idc'));
    });
    Route::post('/wp-admin/C-add', [SystemController::class, 'addCompagnie'])->name('add_compagnie');
    Route::post('/wp-admin/C-edite', [SystemController::class, 'editCompagnie'])->name('edit_compagnie');
    Route::post('/wp-admin/C-compagnie', [SystemController::class, 'viewCompagnieUnique'])->name('listecompagnieU');
    Route::get('/wp-admin/C-login', [SystemController::class, 'login_as_company'])->name('login_as_company');

    Route::get('/wp-admin/publication', [SystemController::class, 'publication'])->name('publication');


    Route::get('/wp-admin/C-compagnie-2', [SystemController::class, 'viewCompagnie'])->name('listecompagnie');
    Route::post('/wp-admin/update', [SystemController::class, 'updateCompagnie'])->name('update_compagnie');
    Route::post('/wp-admin/V-edit', [SystemController::class, 'editVendeur'])->name('edit_vendeur2');
    Route::post('/wp-admin/V-bu', [SystemController::class, 'blockUnblock'])->name('blockunlock');
    Route::post('/wp-admin/V-update', [SystemController::class, 'updateVendeur'])->name('update_vendeur2');
    Route::post('/wp-admin/add_vendeur', [SystemController::class, 'addVendeur'])->name('addvendeur');

    Route::post('/wp-admin/C-abonnement', [abonnementController::class, 'addabonement'])->name('add_abonnement');
    Route::get('/wp-admin/C-abonnementView', function () {
        return view('superadmin.abonnement');
    })->name('add_abonnement1');

    Route::post('/wp-admin/C-abonnementViewNext', [SystemController::class, 'getcompagnie'])->name('add_abonnement2');
    Route::get('/wp-admin/ajouter_lo', [SystemController::class, 'viewajoutelo'])->name('ajouterlowp');
    Route::get('/wp-admin/listelo', [SystemController::class, 'viewlistelo'])->name('wp-listelo');
    Route::post('/wp-admin/addlo', [executeTirageAuto::class, 'executeTirageAuto'])->name('addlo');
    //route for add lot withou job
    Route::get('/wp-admin/HttpAjouter_lo', [SystemController::class, 'HttpViewAjoutelo'])->name('HttpAjouterlowp');
    Route::post('/wp-admin/HttpAddlo', [addTirageHttp::class, 'executeTirageAuto'])->name('HttpAddlo');

    
    
    Route::get('/wp-admin/monitoring', function () {
        return view('superadmin.monitoring');
    })->name('monitoring');

    //run job
    Route::get('/updateLimitPrix', [testjobcontroller::class, 'jobUpdateLimit']);
    Route::get('/updateAutoActive', [testjobcontroller::class, 'jobAutoActive']);




    Route::get('/wp-admin/historiqueabonnement', [abonnementController::class, 'viewhistorique'])->name('historiquesaabonnement');
    Route::get('/wp-admin/facture', [abonnementController::class, 'viewFacture'])->name('facture');
    Route::post('/wp-admin/genererfacture', [abonnementController::class, 'genererFacture'])->name('genererfacture');
    Route::post('/wp-admin/payerfacture', [abonnementController::class, 'payerfacture'])->name('payerfacture');
    Route::post('/wp-admin/regenerate-facture-image', [abonnementController::class, 'regenerateFactureImage'])->name('regenerate_facture_image');
    Route::get('/wp-admin/facture/{id}/regenerate-show', [abonnementController::class, 'regenerateAndShowFacture'])->name('regenerate_facture_show');


    Route::get('/wp-admin/historiquetransaction', [historiquetransaction::class, 'viewtransaction'])->name('historiquestransaction');
    Route::post('/wp-admin/paiementdwe', [abonnementController::class, 'paiementdwe'])->name('paiementsdwe');

    Route::match(['get', 'post'], '/wp-admin/bloquer', [SystemController::class, 'bloquer'])->name('bloquer');
});
