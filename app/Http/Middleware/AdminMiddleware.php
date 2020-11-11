<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
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
        // momentan si adminii si userii simpli au acces la homepage
        // doar adminii vor avea acces la admin page (de creat in viitor si la tag CRUD - in afara de store din Story)
        if (Auth::user()->role == 'admin') {

           return $next($request);
        }
        else {

            return redirect()->back();
        }
        
    }
}
