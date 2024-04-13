<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;

use App\Models\company;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Routing\Matching\ValidatorInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ValidatedInput;
use Validator;
use App\Models\vendeur;
use App\Http\Controllers\api\verificationController as verify;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades;
use Carbon\Carbon;
use App\Models\BoulGagnant;
use App\Models\tirage_record;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Contracts\Providers\Auth as ProvidersAuth;

class AuthController extends Controller
{
    /**
     * login method
     */
   
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login'], ['tirage']]);
    }
    
    public function login(Request $request)
    {

        try {
            $validator = $request->validate([
                "username" => "required",
                "password" => "required",
                "id" => "required",
            ]);
            if (!$validator) {
                return response()->json([
                    'status' => false,
                    "message" => "Erreur de validation",
                ], 422,);
            }
            $user = user::where([
                ['username', '=', $request->input('username')],
                ['android_id', '=', $request->input('id')],
            ])
                ->first();
            if (!$user) {

                return response()->json([
                    'status' => false,
                    "message" => "utilisateur ou android id inccorrect",
                ], 404,);
            }
            if (!Hash::check($request->input('password'), $user->password)) {

                return response()->json([
                    'status' => false,
                    "message" => "mot de passe incorrect",
                ], 404,);
            }
            if ($user->is_block == 1) {
                return response()->json([
                    'status' => false,
                    "message" => "utilisateur blocker",
                ], 404,);
            }
            $credentials = request(['username', 'password']);
            if (!$token = auth()->attempt($credentials)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            $compagnie = company::where([
                ['id', '=', $user->compagnie_id],
            ])->select('name', 'city', 'address', 'phone', 'logo')
                ->first();
            return $this->createNewToken($token, $compagnie);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                "message" => $th->getMessage(),

            ], 500,);
        }
    }
    public function createNewToken($token, $compagnie)
    {

        return response()->json([
            "access_token" => $token,
            "token_type" => "bearer",
           
            'user' => auth()->user(),
            'compagnie' => $compagnie




        ], 200,);
    }
    public function logout()
    {
        auth()->logout();
        return response()->json([

            "message" => "utilisateur déconnecté",

        ]);
    }
    public function tirage(Request $request)
    {



        try {
            $tirage_record = DB::table('tirage_record')->where([

                ['compagnie_id', '=', auth()->user()->compagnie_id],
                ['is_active', '=', '1']
            ])->whereTime(
                'hour',
                '>',
                Carbon::now()->format('H:i:s'),
            )->select('name', 'hour')
                ->orderBy('hour', 'asc')
                ->get();
            
            return response()->json([
                'tirage' => $tirage_record,
                'current_time' => Carbon::now()->format('H:i:s'),


            ]);
        } catch (TokenInvalidException $e) {
            return response()->json(['erreur' => 'token pas valable'], 401);
        } catch (TokenExpiredException $e) {
            return response()->json(['erreur' => 'Token expiré'], 401);
        }
    }
    public function tirage_list(Request $request)
    {



        try {
            $tirage_record = DB::table('tirage_record')->where([

                ['compagnie_id', '=', auth()->user()->compagnie_id],
            ])
               ->select('name')
                ->get();
        
            return response()->json([
                "status"=>'true',
                "code"=>"200",
                'tirage' => $tirage_record,

            ], 200);
        } catch (TokenInvalidException $e) {
            return response()->json(['erreur' => 'token pas valable'], 401);
        } catch (TokenExpiredException $e) {
            return response()->json(['erreur' => 'Token expiré'], 401);
        }
    }
    public function tirage_result(Request $request)
    {



        try {
            $validator = $request->validate([
                "tirage" => "required",

            ]);
            $tirage_record = tirage_record::where([
                ['compagnie_id', '=', auth()->user()->compagnie_id],
                ['is_active', '=', '1'],
                ['name', '=', $request->input('tirage')]
            ])->first();
            if($tirage_record){
                 $result = BoulGagnant::where([
                    ['compagnie_id', '=', auth()->user()->compagnie_id], 
                    ['tirage_id', '=', $tirage_record->id]
                ])->orderByDesc('id')
                 ->limit('100')
                 ->select('created_ as date','unchiffre as loto','premierchiffre as boul1','secondchiffre as boul2','troisiemechiffre as boul3')
                 ->get();

                 return response()->json([
                    "status"=>'true',
                    "code"=>"200",
                    'resultat' => [
                        $result
                    ],
    
                ], 200);
 
            }else{
                return response()->json([
                    "status"=>'true',
                    "code"=>"200",
                    'resultat' => [
                    
                    ],
    
                ], 200);

            }
              
        
           
        } catch (TokenInvalidException $e) {
            return response()->json(['erreur' => 'token pas valable'], 401);
        } catch (TokenExpiredException $e) {
            return response()->json(['erreur' => 'Token expiré'], 401);
        }
    }
    public function profil()
    {

        return response()->json(auth()->user());
    }
}
