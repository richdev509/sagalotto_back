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
            ])->first();

            if (!$user) {
                $user1 = user::where([
                    ['username', '=', $request->input('username')],
                ])->first();
                if ($user1) {
                    if ($user1->android_id == '1' && Hash::check($request->input('password'), $user1->password)) {
                        $fuser  = User::find($user1->id);
                        $fuser->android_id = $request->input('id');
                        $fuser->save();

                        $user = user::where([
                            ['username', '=', $request->input('username')],
                            ['android_id', '=', $request->input('id')],
                        ])->first();
                    } else {
                        return response()->json([
                            'status' => false,
                            "message" => "utilisateur ou android id inccorrect",
                        ], 404,);
                    }
                } else {
                    return response()->json([
                        'status' => false,
                        "message" => "utilisateur ou android id inccorrect",
                    ], 404,);
                }
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
            $setting = DB::table('setings')->where([
                ['compagnie_id', '=', $user->compagnie_id],
            ])->first();
            $branche = DB::table('branches')->where('id', $user->branch_id)->first();
            if ($branche) {
                if (!empty($branche->address) && $branche->address !== '0' && $branche->address !== 'inconnu') {
                    $compagnie->address = $branche->address;
                }

                if (!empty($branche->phone) && $branche->phone !== '0' && $branche->phone !== 'inconnu') {
                    $compagnie->phone = $branche->phone;
                }
            }

            if ($setting) {
                if ($setting->show_address == 0) {
                    $compagnie->address = '';
                }
                if ($setting->show_phone == 0) {
                    $compagnie->phone = '';
                }
                if ($setting->show_logo == 0) {
                    $compagnie->logo = '0';
                }
                if ($setting->show_name == 0) {
                    $compagnie->name = '';
                }
            } else {
                $setting = new \stdClass();
                $setting->show_address = 1;
                $setting->show_phone = 1;
                $setting->show_logo = 1;

                $setting->show_name = 1;
                $setting->show_footer = 1;
                $setting->show_mariage_price = 1;
                $setting->qt_bolet = 100;
                $setting->qt_maryaj = 100;
                $setting->qt_loto3 = 100;
                $setting->qt_loto4 = 100;
                $setting->qt_loto5 = 100;
            }

            return $this->createNewToken($token, $compagnie, $setting);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                "message" => $th->getMessage(),

            ], 500,);
        }
    }
    public function createNewToken($token, $compagnie, $setting)
    {

        return response()->json([
            "access_token" => $token,
            "token_type" => "bearer",

            'user' => auth()->user(),
            'compagnie' => $compagnie,
            'setting' => $setting,




        ], 200,);
    }
    protected function invalidateTokens(User $user)
    {
        // If using JWT, you can implement a token blacklist or store the token version in the database
        // Example: Increment the token version to invalidate all previous tokens
        $user->token_version = $user->token_version + 1;
        $user->save();

        // Alternatively, you can store invalidated tokens in a cache (e.g., Redis)
        // $blacklist = Cache::get('jwt_blacklist', []);
        // $blacklist[] = $currentToken; // Add the current token to the blacklist
        // Cache::put('jwt_blacklist', $blacklist);
    }
    public function logout()
    {
        auth()->logout();
        return response()->json([

            "message" => "utilisateur déconnecté",

        ]);
    }
    public function update()
    {
        return response()->json([
            "status" => true,
            "latest_version" => '1.0.4',
            "url" => 'https://sagaloto.com/sagaloto_v1.0.3',
            "code" => 200,
        ], 200);
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
            )->whereTime(
                'hour_open',
                '<',
                Carbon::now()->format('H:i:s'),
            )
                ->select('name', 'hour')
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
                "status" => 'true',
                "code" => "200",
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
            if ($tirage_record) {
                $result = BoulGagnant::where([
                    ['compagnie_id', '=', auth()->user()->compagnie_id],
                    ['tirage_id', '=', $tirage_record->id]
                ])->orderByDesc('id')
                    ->limit('100')
                    ->select('created_ as date', 'unchiffre as loto', 'premierchiffre as boul1', 'secondchiffre as boul2', 'troisiemechiffre as boul3')
                    ->get();

                return response()->json([
                    "status" => 'true',
                    "code" => "200",
                    'resultat' => [
                        $result
                    ],

                ], 200);
            } else {
                return response()->json([
                    "status" => 'true',
                    "code" => "200",
                    'resultat' => [],

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
