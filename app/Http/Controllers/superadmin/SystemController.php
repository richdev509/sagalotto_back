<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\company;
use App\Models\tbladmin;
use App\Models\User;
use App\Models\abonnementhistoriqueuser;
use App\Models\ticket_code;
use App\Http\Controllers\superadmin\abonnementController;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class SystemController extends Controller
{

    public function viewadmin()
    {
        if (session('role') == "admin" || session('role') == "comptable") {
            $Compagnie = DB::table('companies')->where('type', 'production')->get();
            $nombreCompagnie = $Compagnie->count();
            $id = DB::table('companies')->where('type', 'production')->pluck('id')
                ->toArray();
            $nombrePos = User::whereIn('compagnie_id', $id)->count();
            $Compagnieinactive = DB::table('companies')->where('type', 'production')->where('is_active', 0)->count();
        } else {
            $Compagnie = DB::table('companies')->where('type', 'production')->where('actionUser', session('id'))->get();
            $nombreCompagnie = $Compagnie->count();
            $id = DB::table('companies')->where('type', 'production')->where('actionuser', session('id'))->pluck('id')
                ->toArray();
            $nombrePos = User::whereIn('compagnie_id', $id)->count();
            $Compagnieinactive = DB::table('companies')->where('type', 'production')->where('actionuser', session('id'))->where('is_active', 0)->count();
        }
        return view('superadmin.admin', compact('nombreCompagnie', 'nombrePos', 'Compagnieinactive'));
    }

    public function viewajoutelo()
    {
        $list = DB::table('tirage')->get();
        return view('superadmin.ajouter_lo', compact('list'));
    }
    public function auth2(Request $request)
    {
        $username =  $request->input('username');
        $password =  $request->input('password');

        if (empty($username) || empty($password)) {
            notify()->error('ranpli tout chan yo');
            return back();
        } else {

            $user = tbladmin::where([
                ['username', '=', $username],
            ])->first();
            //try to know if user
            if ($user) {
                //User found let tcheck the password
                if (Hash::check($password, $user->password)) {
                    //Password match let find if user not block
                    if ($user->is_block != '1') {
                        $request->session()->put('id', $user->id);
                        $request->session()->put('fullname', $user->fullname);
                        $request->session()->put('logo', $user->photopath);
                        $request->session()->put('role', $user->role);
                        notify()->success('Bienvenue' . $user->name);
                        return redirect('/wp-admin/admin');
                    } else {
                        notify()->error('kont ou bloke kontakte sagacelotto');
                        return redirect('/wp-admin/login');
                    }
                } else {
                    notify()->error('modepass la pa bon');

                    return redirect('/wp-admin/login');
                }
            } else {
                notify()->error('non itilizate a inkorek');

                return redirect('/wp-admin/login')->with('error', 'Utilisateur non trouve');
            }
        }
    }

    public function nombrevendeurmois($data){
        $results = [];
            foreach ($data as $da) {
                $date = Carbon::parse($da->dateplan);
                $month = $date->month;
                $year = $date->year;
    
                $distinctUserCount = DB::table('ticket_code')
                    ->where('compagnie_id', $da->id)
                    ->whereMonth('created_at', $month)
                    ->whereYear('created_at', $year)
                    ->distinct('user_id')
                    ->count('user_id');
    
                $results[$da->id] = $distinctUserCount;
            }
            return $results;
    }
    public function montantplan($data){
        $resultsmontant = [];
            foreach ($data as $da) {
                $distinctUserCount = DB::table('abonnementhistorique')
                    ->where('idcompagnie', $da->id)
                    ->where('dateabonnement', $da->dateplan)
                    ->value('montant');
    
                $resultsmontant[$da->id] = $distinctUserCount;
            }
            return $resultsmontant;
    }
    public function viewCompagnie()
    {
        if (session('role') == "admin" || session('role') == "comptable") {
            $data = DB::table('companies')->get();
            $results=$this->nombrevendeurmois($data);
            $resultsmontant=$this->montantplan($data);
            if ($data) {
                return view('superadmin.liste_compagnie', compact('data','results','resultsmontant'));
            } else {
                notify()->error('Compagnie non trouver');
                return back();
            }
        }



        if (session('role') == "admin2") {
            $data = DB::table('companies')->where('actionUser', session('id'))->get();
            $results=$this->nombrevendeurmois($data);
            if ($data) {
                return view('superadmin.liste_compagnie', compact('data','results'));
            } else {
                notify()->error('Compagnie non trouver');
                return back();
            }
        }
    }

    public function viewCompagnieUnique(Request $request)
    {

        $data = DB::table('companies')->where('id', $request->idcompagnie)->first();

        $datas = User::where('compagnie_id', $request->idcompagnie)->where('is_delete', 0)->get();

        if ($data) {
            return view('superadmin.listecompagnie', compact('data', 'datas'));
        } else {
            notify()->error('Compagnie non trouver');
            return back();
        }
    }
    public function addCompagnie(Request $request)
    {

        $verifier = company::where('name', $request->compagnie)
            ->orWhere('phone', $request->phone)
            ->exists();
        $verifierusername = company::where('username', $request->user)->exists();
        if ($verifierusername) {
            notify()->error('username non accepter');
            return back();
        }
        if ($verifier) {
            notify()->error('Compagnie existe');
            return back();
        }
        $reponse = DB::table('companies')->insertGetId([
            'name' => $request->compagnie,
            'address' => $request->adress,
            'city' => $request->city,
            'phone' => $request->phone,
            'email' => $request->email,
            'plan' => $request->plan,
            'username' => $request->user,
            'actionUser' => session('id'),
            'is_active' => '0',
            'password' => Hash::make($request->input('password')),
        ]);
        $company = company::find($reponse);
        $company->code = "CO-00" . $reponse;
        $company->save();
        if ($reponse) {
            notify()->success('Compagnie add success');
            return redirect()->route('listecompagnie');
        }
    }


    public function editCompagnie(Request $request)
    {

        $data = DB::table('companies')->where('id', $request->id)->first();

        if ($data) {
            return view('superadmin.edit_compagnie', compact('data'));
        } else {
            notify()->error('Compagnie non trouver');
            return back();
        }
    }

    public function updateCompagnie(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required|exists:companies,id',
            'compagnie' => 'required|string|max:255',
            'adresse' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'plan' => 'required|string|max:255',
            'info' => 'nullable|string',
        ]);

        // Trouver la compagnie par ID
        $company =Company::find($request->id);

        // Vérifier si la compagnie existe
        if (!$company) {
            return redirect()->route('listecompagnie')->withErrors(['error' => 'Compagnie non trouvée.']);
        }

        // Mise à jour des données de la compagnie
        $company->update([
            'name' => $request->compagnie,
            'address' => $request->adresse,
            'city' => $request->city,
            'phone' => $request->phone,
            'email' => $request->email,
            'plan' => $request->plan,
            'info' => $request->info,
        ]);

        // Notification de succès et redirection
        notify()->success('Modification avec succès');
        return redirect()->route('listecompagnie');
    }


    public function updatevendeur(Request $request)
    {
        $validator = $request->validate([
            "name" => "required|max:50",
            'bank_id' => 'required',
            'phone' => 'required|numeric',
            'percent' => 'required|max:100|numeric',
            "bank_name" => "required|max:30",



        ]);

        $vendeur = user::where([
            ['compagnie_id', '=', $request->idcompagnie],
            ['id', '=', $request->input('id')]
        ])->first();
        if (!$vendeur) {
            notify()->error('vande sa pa trouve');

            return redirect()->route('listecompagnie');
        }

        if ($request->input('block') == '1') {
            $status = 1;
        } else {
            $status = 0;
        }
        if (!empty($request->input('password'))) {
            $user = user::where('id', $request->input('id'))->update([
                'name' => $request->input('name'),
                'address' => $request->input('address'),
                'gender' => $request->input('gender'),
                'phone' => $request->input('phone'),
                //'username' => $request->input('username'),  
                'percent' => $request->input('percent'),
                'android_id' => $request->input('bank_id'),
                'bank_name' => $request->input('bank_name'),
                'password' => Hash::make($request->input('password')),
                'is_block' => $status,
                'created_at' => Carbon::now()
            ]);

            notify()->success('modifikasyon an fet avek siksè');

            return redirect()->route('listecompagnie');
        } else {
            $user = user::where('id', $request->input('id'))->update([
                'name' => $request->input('name'),
                'address' => $request->input('address'),
                'gender' => $request->input('gender'),
                'phone' => $request->input('phone'),
                //'username' => $request->input('username'),  
                'percent' => $request->input('percent'),
                'android_id' => $request->input('bank_id'),
                'bank_name' => $request->input('bank_name'),
                //'password' => Hash::make($request->input('bank_name')),
                'is_block' => $status,
                'created_at' => Carbon::now()
            ]);

            notify()->success('modifikasyon an fet avek siksè');

            return redirect()->route('listecompagnie');
        }
    }

    public function addvendeur(Request $request)
    {
        $validator = $request->validate([
            "name" => "required|max:50",
            'bank_id' => 'required',
            'phone' => 'required|numeric',
            'percent' => 'required|max:100|numeric',
            "bank_name" => "required|max:30",
            "password" => "required|max:20",
            "username" => "required|max:20|unique:users"

        ]);

        $vendeur = user::where([
            ['compagnie_id', '=', $request->idcompagnie],
            ['bank_name', '=', $request->bank_name]
        ])->first();
        if ($vendeur) {
            notify()->error('Gen yon bank ak non sa deja');
            redirect()->route('listecompagnie');
        }

        if ($request->input('block') == '1') {
            $status = 1;
        } else {
            $status = 0;
        }
        $query = DB::table('users')->insertGetId([
            'compagnie_id' => $request->idcompagnie,
            'name' => $request->input('name'),
            'address' => $request->input('address'),
            'gender' => $request->input('gender'),
            'phone' => $request->input('phone'),
            'username' => $request->input('username'),
            'percent' => $request->input('percent'),
            'android_id' => $request->input('bank_id'),
            'bank_name' => $request->input('bank_name'),
            'password' => Hash::make($request->input('password')),
            'is_block' => $status,
            'created_at' => Carbon::now()
        ]);
        $user = user::find($query);
        $user->code = "V-00" . $query;
        $user->save();
        notify()->success('Vandè a anregistre avec siksè');
        return redirect()->route('listecompagnie');
    }
    public function editvendeur(Request $request)
    {

        $data = User::where('compagnie_id', $request->id)->where('id', $request->iduser)->where('is_delete', 0)->first();
        $Compagnie = $request->compagnie;
        if ($data) {
            return view('superadmin.editer_vendeur', compact('data', 'Compagnie'));
        }
    }


    public function logout()
    {
        if (Session('id')) {
            Session()->forget('id');
            Session()->forget('fullname');
            Session()->forget('logo');
            Session()->forget('role');
            return redirect()->route('wplogin');
        } else {
            return redirect()->route('wplogin');
        }
    }

    public function blockUnblock(Request $request)
    {
        $reponse = $this->changeStatus($request->id);
        if ($reponse == true) {
            notify()->success('Success');
        } else {
            notify()->error('Erreur');
        }
        return redirect()->route('listecompagnie');
    }
    public function changeStatus($id)
    {
        $user = User::find($id);

        if ($user) {
            $user->is_block = $user->is_block == '1' ? '0' : '1';
            if ($user->save()) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }



    public function addabonement(Request $request)
    {


        if (session('role') == "admin" || session('role') == "addeur") {


            $reponse = Company::where('name', $request->name)->where('phone', $request->phone)->first();

            if ($reponse) {
                $retour = $this->getDaysRemaining($reponse->dateplan, $reponse->dateexpiration);
                //$datedebutplan = Carbon::parse($request->date);
                //$datedebutplanI = Carbon::parse($request->date);

                $datedebutplan = Carbon::parse($reponse->dateexpiration);
                $dureemois = $request->duree;

                $dateExpirations = Carbon::parse($reponse->dateexpiration);
                $dateExpirationS = Carbon::parse($reponse->dateexpiration);
                $nouvelleDate = $request->date ? Carbon::parse($request->date) : null;
                $nouvelleDate2 = $request->date ? Carbon::parse($request->date) : null;


                if (!$nouvelleDate) {

                    $dateexpiration = $dateExpirations->addMonths($dureemois)->addDays(10);
                    $datedebut = $dateExpirationS->addDays(1);
                } else {
                    if ($nouvelleDate->lessThan($dateExpirationS)) {
                        notify()->error('La nouvelle date est inférieure à la date d\'expiration.');
                        return redirect()->route('listecompagnie');
                    }
                    $dateexpiration = $nouvelleDate->addMonths($dureemois)->addDays(10);
                    $datedebut = $nouvelleDate2;
                }



                // Mettre à jour l'abonnement dans la base de données
                $reponse->update([
                    'dateplan' => $datedebut->format('Y-m-d'),
                    'dateexpiration' => $dateexpiration->format('Y-m-d'),
                    'is_block' => '0',
                ]);

                $query = abonnementhistoriqueuser::insertGetId([
                    'idcompagnie' => $reponse->id,
                    'iduser' => session('id'),
                    'nombremois' => $request->duree,
                    'date' => Carbon::now()->format('Y-m-d'),
                    'action' => 'Ajoute Abonnement',
                    'created_at' => Carbon::now()
                ]);

                notify()->success('Abonnement mis à jour avec succès');
                return redirect()->route('listecompagnie');
            } else {
                notify()->error('Compagnie non trouvée');
                return redirect()->route('listecompagnie');
            }
        } else {
            notify()->error('Vous n\'avez pas access a niveau');
            return redirect()->route('listecompagnie');
        }
    }

    // Méthode getDaysRemaining à implémenter selon votre logique


    function getDaysRemaining($dateplan, $datefin)
    {

        $startDate = Carbon::parse($dateplan);
        $endDate = Carbon::parse($datefin);
        $currentDate = Carbon::now();

        // Vérifier si l'abonnement est encore valide
        if ($currentDate->between($startDate, $endDate)) {
            $daysRemaining = $currentDate->diffInDays($endDate);
            return  $daysRemaining;
        } else {
            return $d = 0;
        }
    }

    public function getcompagnie(Request $request)
    {
        if (session('id')) {
            $data = company::where('id', $request->id)->first();
            if ($data) {
                return view('superadmin.abonnement', compact('data'));
            } else {
                notify()->error('partie recuperation compagnie');
                return back();
            }
        }
    }
}
