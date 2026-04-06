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
        if (Session('loginId')) {
            $branch = branch::where([
                ['compagnie_id', '=', Session('loginId')],
                ['is_delete', '=', 0]
            ])->get();
            return view('lister_branch', ['branch' => $branch]);
        } else {
            return view('login');
        }
    }
    public function edit_branch(Request $request)
    {
        if (Session('loginId')) {
            $branch = branch::where([
                ['compagnie_id', '=', Session('loginId')],
                ['id', '=', $request->input('id')],
                ['is_delete', '=', 0]
            ])->first();
            if (!$branch) {
                notify()->error('Gen yon ere ki pase');
                return back();
            }
            return view('editer_branch', ['branch' => $branch]);
        } else {
            return view('login');
        }
    }
    public function update_branch(Request $request)
    {
        if (Session('loginId')) {

            $validator = $request->validate([
                "name" => "required|max:50",
                'phone' => 'required|numeric|',
                'address' => 'required|max:70',

            ]);
            $vendeur = branch::where([
                ['compagnie_id', '=', Session('loginId')],
                ['id', '=', $request->input('id')]
            ])->first();
            if (!$vendeur) {
                notify()->error('branch sa pa trouve');
                return back();
            }
            // Check percentage type and set percent_agent_only and percent
            $percentAgentOnly = 0;
            $percent = $request->input('percent_value', 0);
            
            if ($request->input('percentage_type') === 'agent_only') {
                $percentAgentOnly = 1;
            }

            $updateData = [
                'name' => $request->input('name'),
                'address' => $request->input('address'),
                'phone' => $request->input('phone'),
                'description' => $request->input('description'),
                'agent_fullname' => $request->input('agent_fullname'),
                'agent_username' => $request->input('agent_username'),
                'percent_agent_only' => $percentAgentOnly,
                'percent' => $percent,
                'updated_at' => Carbon::now(),
                'is_block' => $request->input('is_block', 0),
            ];
            if (!empty($request->input('agent_password'))) {
                $updateData['agent_password'] = Hash::make($request->input('agent_password'));
            }
            branch::where('id', $request->input('id'))->update($updateData);
            notify()->success('modifikasyon an fet avek siksè');
            return back();
        } else {
            return view('login');
        }
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
                notify()->error('ou gen yon branch ak non sa deja');
                return back();
            }

            // Check percentage type and set percent_agent_only and percent
            $percentAgentOnly = 0;
            $percent = $request->input('percent_agent_only', 0);
            
            if ($request->input('percentage_type') === 'agent_only') {
                $percentAgentOnly = 1;
            }

            $query = DB::table('branches')->insertGetId([
                'compagnie_id' => Session('loginId'),
                'name' => $request->input('name'),
                'address' => $request->input('address'),
                'phone' => $request->input('phone'),
                'agent_username' => $request->input('agent_username'), 
                'agent_fullname' => $request->input('agent_name'),              
                'agent_password' => Hash::make($request->input('agent_password')),
                'percent_agent_only' => $percentAgentOnly,
                'percent' => $percent,
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
