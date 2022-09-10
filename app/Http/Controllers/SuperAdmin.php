<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use DB;
use Session;
use Auth;
use Illuminate\Support\Facades\Hash;
class SuperAdmin extends Controller
{
    public function alluser(Request $request)
    {
        $alluser=DB::table('users')->orderBy('id', 'DESC')->get();
    	return view('superadmin.alluser', compact("alluser"));
    }
    public function adduser(Request $request)
    {
    	return view('superadmin.adduser');
    }
    public function edituser(Request $request)
    {
    	$id=$request->user_id;
    	$single_user=DB::table('users')
            ->where('id', '=', $id)
            ->first();
    	return view('superadmin.edituser', compact("single_user"));
    }
    public function definesmc(Request $request)
    {
        $alluser=DB::table('users')->where('user_type', '=' , "user")->orderBy('id', 'DESC')->get();
    	$allSmcMember=DB::table('users')->where('user_type', '=',"SMC")->orderBy('id', 'DESC')->get();
    	return view('superadmin.definesmc', compact("allSmcMember", "alluser"));
    }

    //Post Function
    public function add_user(Request $request)
    {
    	$password=$request->password;
        $conform_password=$request->conform_password;
        if($password!=$conform_password)
        {
        	return redirect('/adduser');
        }
        // if(DB::table('users')->where('email'))
    	$data['first_name']=$request->first_name;
        $data['last_name']=$request->last_name;
        $data['email']=$request->email;
        $data['user_type']="user";
        $data['password']=Hash::make($request->password);
        $data['phone']=$request->phone;
        $data['status']=$request->status;
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        DB::table('users')->insert($data);
        Session::flash('key', 'Successfully Registered');
        Session::flash('message', 'Record Save Successfully.'); 
        return redirect('/alluser');
    }
    public function edit_user_save(Request $request)
    {
        $id=$request->id;
        $data['first_name']=$request->first_name;
        $data['last_name']=$request->last_name;
        $data['email']=$request->email;
        $data['password']=Hash::make($request->password);
        $data['phone']=$request->phone;
        $data['status']=$request->status;
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        DB::table('users')
            ->where('id', '=', $id)
            ->update($data);
        Session::flash('key', 'Successfully Updated');
        Session::flash('message', 'Record Updated Successfully.'); 
        return redirect('/alluser');
    }
    public function add_smc_member(Request $request)
    {
        $users=$request->users;
        $data['user_type']="SMC";
    	foreach ($users as $user1) 
        {
            DB::table('users')->where('id', '=', $user1)->update($data);
        }
        Session::flash('key', 'Added Successfully');
        Session::flash('message', 'SMC Member Added Successfully.');
        return redirect('/definesmc');
    }
    public function check_email(Request $request)
    {
        $email = DB::table('users')
        ->where('email', '=', $request->email)
        ->first();
        if($email)
        {
            echo 1;
        }
        else
        {
            echo 0;
        }
    }
    public function deleteSmc(Request $request)
    {
        $id=$request->id;
        $data['user_type']="user";
        DB::table('users')->where('id', '=', $id)->update($data);
        Session::flash('key', 'Deleted Successfully');
        Session::flash('message', 'SMC Member Deleted Successfully.');
        return redirect('/definesmc');
    }
    public function forgetpasswordSA(Request $request)
    {
        return view('superadmin.forgetpassword');
    }
    public function editProfile(Request $request)
    {
        if(Auth::check()!=null)
        {
            $current_user_id=Auth::user()->id;
            $single_user=DB::table('users')
                ->where('id', '=', $current_user_id)
                ->first();
            return view('superadmin.edit_profile', compact("single_user"));
        }
        else
        {
            return redirect('/');
        }
    }
    public function edit_profile_save(Request $request)
    {
        if(Auth::check()!=null)
        {
            $current_user_id=Auth::user()->id;
            $id=$request->id;
            $data['first_name']=$request->first_name;
            $data['last_name']=$request->last_name;
            $data['email']=$request->email;
            $data['password']=Hash::make($request->password);
            $data['phone']=$request->phone;
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['updated_at'] = date('Y-m-d H:i:s');
            DB::table('users')
                ->where('id', '=', $current_user_id)
                ->update($data);
            Session::flash('key', 'Successfully Updated');
            Session::flash('message', 'Profile Updated Successfully.'); 
            return redirect('/home');
        }
        else
        {
            return redirect('/');
        }
    }
}
