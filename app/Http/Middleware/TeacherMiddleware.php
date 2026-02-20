<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class TeacherMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Auth::check() - tizimga kirganmi?
        // Auth::user()->role === 'teacher' - teacher mi?
        if (Auth::check() && Auth::user()->role === 'teacher') {
            return $next($request);
        }

        // Agar teacher bo'lmasa
        abort(403, 'Sizda teacher ruxsati yo\'q!');
    }
}
