<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                return $this->redirectForGuard($guard);
            }
        }

        // Garantimos que o guard de empresa também seja verificado mesmo
        // quando o middleware for chamado sem parâmetros (guest padrão).
        if (!in_array('empresa', $guards, true) && Auth::guard('empresa')->check()) {
            return $this->redirectForGuard('empresa');
        }

        return $next($request);
    }

    protected function redirectForGuard(?string $guard)
    {
        if ($guard === 'empresa') {
            return redirect()->route('enterprises.dashboard');
        }

        return redirect(RouteServiceProvider::HOME);
    }
}
