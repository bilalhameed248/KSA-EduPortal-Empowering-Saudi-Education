<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
class branchmanager
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
        if (Auth::check()!=null && Auth::user()->user_type=="SMC") 
        {
            return $next($request);
        }
        else
        {
            return redirect('/');
        }
    }
}
