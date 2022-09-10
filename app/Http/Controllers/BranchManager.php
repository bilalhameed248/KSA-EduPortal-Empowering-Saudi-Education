<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;
class BranchManager extends Controller
{
    public function addbranch(Request $request)
    {
    	$branchprinciple=DB::table('users')->where('user_type', '=' , "user")->orderBy('id', 'DESC')->get();
    	return view('branchmanager.addbranch', compact("branchprinciple"));
    }
    public function allbranch(Request $request)
    {
    	$allbranch = DB::table('branch')
            ->join('users', 'branch.branch_principle', '=', 'users.id')
            ->orderBy('branch.branch_id', 'DESC')
            ->get();
    	return view('branchmanager.allbranch', compact("allbranch"));
    }
    public function editbranch(Request $request)
    {
    	$id=$request->branch_id;
    	$specific_branch = DB::table('branch')
    		->join('users', 'branch.branch_principle', '=', 'users.id')
	    	->where('branch.branch_id', '=', $id)
	    	->first();
	    $branchprinciple=DB::table('users')->where('user_type', '=' , "user")->orderBy('id', 'DESC')->get();
    	return view('branchmanager.editbranch', compact("specific_branch", "branchprinciple"));
    }
    //POST FUNCTION
    public function add_branch(Request $request)
    {
    	$this->validate($request,[
            'branchprinciple'=>'required'
        ]);
    	$data['branch_name']=$request->branchname;
        $data['branch_city']=$request->branchcity;
        $data['branch_principle']=$request->branchprinciple;
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        DB::table('branch')->insert($data);
        $data1['user_type']="principle";
        DB::table('users')
            ->where('id', '=', $request->branchprinciple)
            ->update($data1);
        Session::flash('key', 'Added Successfully');
        Session::flash('message', 'Branch Added Successfully.'); 
        return redirect('/allbranch');
    }
    public function update_branch(Request $request)
    {
    	$id=$request->id;
    	if($request->branchprinciple==null)
    	{
    		$bpid=$request->branch_principle_id;
    	}
    	else
    	{
    		$bpid=$request->branchprinciple;
    	}
        // dd($request->branch_principle_id." OR ".$bpid);
    	$data['branch_name']=$request->branchname;
        $data['branch_city']=$request->branchcity;
        $data['branch_principle']=$bpid;
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        DB::table('branch')
            ->where('branch_id', '=', $id)
            ->update($data);

        //Update user Table To set user_type To branch manager
        $data1['user_type']="principle";
        DB::table('users')
            ->where('id', '=', $bpid)
            ->update($data1);
        $data2['user_type']="user";
        if($bpid!=$request->branch_principle_id)
        {
            DB::table('users')
                ->where('id', '=', $request->branch_principle_id)
                ->update($data2);
        }
        Session::flash('key', 'Updated Successfully');
        Session::flash('message', 'Branch Updated Successfully.'); 
        return redirect('/allbranch');
    }
}
