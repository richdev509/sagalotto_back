<?php

namespace App\Http\Controllers;

use App\Models\branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
class branchController extends Controller
{
    public function create_branch()
    {
            return view('ajouter_branch');
    }
    public function index_branch()
    {
            return view('ajouter_branch');
    }
    public function edit_branch()
    {
            return view('ajouter_branch');
    }
    public function update_branch()
    {
            return view('ajouter_branch');
    }
    public function store_branch(Request $request)
    {
        if (Session('loginId')) {
            $validator = $request->validate([
                "name" => "required|max:50",
                'phone' => 'required|numeric|',
                'address' => 'required|max:100',
                "agent_username" => "required|max:20|unique:branches"

            ]);
            $branch = branch::where([
                ['compagnie_id', '=', Session('loginId')],
                ['name', '=', $request->name]
            ])->first();
            if ($branch) {
                notify()->error('ou gen yon bank ak non sa deja');
                return back();
            }

            $query = DB::table('branches')->insertGetId([
                'compagnie_id' => Session('loginId'),
                'name' => $request->input('name'),
                'address' => $request->input('address'),
                'phone' => $request->input('phone'),
                'agent_username' => $request->input('username'), 
                'agent_fullname' => $request->input('agent_name'),              
                'agent_password' => Hash::make($request->input('password')),  
                'created_at' => Carbon::now()
            ]);
            $branch = branch::find($query);
            $branch->code = "B-00" . $query;
            $branch->save();
            notify()->success('Branch lan anregistre avec siksè');
            return back();
        } else {
            return view('login');
        }
    }
}
