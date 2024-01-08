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
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades;

use Tymon\JWTAuth\Contracts\Providers\Auth as ProvidersAuth;

class AuthController extends Controller
{
    /**
     * login method
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
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
            ])->select('name','city','address','phone','logo')
            ->first();
            return $this->createNewToken($token, $compagnie);
        } catch (\Throwable $th) {
            return response()->json([
                'status' =>false,
                "message" => $th->getMessage(),

            ], 500,);
        }
    }
    public function createNewToken($token, $compagnie)
    {   
        
        return response()->json([
            "access_token" => $token,
            "token_type" => "bearer",
            //'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user(),
            'compagnie'=> $compagnie




        ], 200,);
    }
    public function logout()
    {
        auth()->logout();
        return response()->json([

            "message" => "utilisateur déconnecté",

        ]);
    }
    public function profil()
    {
       
       return response()->json(auth()->user());
    }

    
}
