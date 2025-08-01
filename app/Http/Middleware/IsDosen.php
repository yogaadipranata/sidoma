<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class IsDosen
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
      if (Auth::check() && Auth::user()->role === 'dosen') {
        return $next($request);
      }

        return redirect('/dashboard')->with('error', 'Akses ditolak. Anda bukan Dosen.');
    }
}
