<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
class GradeManager
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
        if (Auth::check()!=null && Auth::user()->user_type=="headmaster") 
        {
            return $next($request);
        }
        else
        {
            return redirect('/');
        }
    }
}
