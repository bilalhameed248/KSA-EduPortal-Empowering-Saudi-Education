<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
class ReportManager
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check()!=null && (Auth::user()->user_type=="superadmin" || Auth::user()->user_type=="principle" || Auth::user()->user_type=="viceprinciple" || Auth::user()->user_type=="headmaster" || Auth::user()->user_type=="leader" || Auth::user()->user_type=="supervisor" || Auth::user()->user_type=="teacher")) 
        {
            return $next($request);
        }
        else
        {
            return redirect('/');
        }
    }
}
