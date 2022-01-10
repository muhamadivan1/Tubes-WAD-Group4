<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next) //authentikasi untuk mengarah ke menu petugas
    {
        if ($request->user()->level == "Petugas") {
            return redirect('/petugas');
        } else {
            return $next($request);
        }
    }
}
