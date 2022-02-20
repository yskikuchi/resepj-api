<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  ...$guards
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;
        $path = $request->server('PATH_INFO');
        foreach ($guards as $guard) {
            if(Auth::guard('admin')->check()){
                if($path != '/admin/logout'){
                    return redirect('/admin/dashboard');
                }
            }
            if (Auth::guard($guard)->check()) {

                // return redirect(RouteServiceProvider::HOME);
                if ($request->expectsJson()) {
                    return response()->json(Auth::user(), 200);
                }
            }
        }

        return $next($request);
    }
}
