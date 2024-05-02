<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\company;
use App\Models\User;

class SystemController extends Controller
{
    public function login(Request $request)
    {
        $username =  $request->input('username');
        $password =  $request->input('password');

        if (Empty($username) || Empty($password)) {
            notify()->error('ranpli tout chan yo');
            return back();
        } else {

            $user = company::where([
                ['username', '=', $username],
            ])->first();
            //try to know if user
            if ($user) {
                //User found let tcheck the password
                if (Hash::check($password, $user->password)) {
                    //Password match let find if user not block
                    if ($user->is_block != '1') {
                        $request->session()->put('loginId', $user->id);
                        $request->session()->put('name', $user->name);
                        $request->session()->put('logo', $user->logo);
                        notify()->success('Bienvenue  ' . $user->name);
                        return redirect('/admin');
                    } else {
                        notify()->error('kont ou bloke kontakte sagacelotto');
                        return redirect('/login');
                    }
                } else {
                    notify()->error('modepass la pa bon');

                    return redirect('/login');
                }
            } else {
                notify()->error('non itilizate a inkorek');

                return redirect('/login')->with('error', 'Utilisateur non trouve');
            }
        }
    }
}
