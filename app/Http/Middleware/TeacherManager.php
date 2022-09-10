<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
class TeacherManager
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
        if (Auth::check()!=null && Auth::user()->user_type=="supervisor") 
        {
            return $next($request);
        }
        else
        {
            return redirect('/');
        }
    }
}
