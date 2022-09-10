<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
class BlockManager
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
        if (Auth::check()!=null && Auth::user()->user_type=="viceprinciple") 
        {
            return $next($request);
        }
        else
        {
            return redirect('/');
        }
    }
}
