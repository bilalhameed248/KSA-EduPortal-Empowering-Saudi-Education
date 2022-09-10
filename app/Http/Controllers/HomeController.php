<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if(Auth::user()->user_type=="superadmin" || Auth::user()->user_type=="SMC" || Auth::user()->user_type=="principle" || Auth::user()->user_type=="viceprinciple" || Auth::user()->user_type=="headmaster" || Auth::user()->user_type=="leader" || Auth::user()->user_type=="supervisor" || Auth::user()->user_type=="teacher" || Auth::user()->user_type=="student")
        {
            return view('superadmin.app');
        }
        else if(Auth::user()->user_type=="user")
        {
            Auth::logout();
            return redirect('/');
        }
        else
        {
            return redirect('/');
        }
    }
}
