<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RedirectBaseOnRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (! $request->routeIs('direction.show')) {
            $user = $request->user();

            if ($user && $user->hasRole(['driver', 'student'])) {
                return redirect()->route(
                    'direction.show',
                    ['direction' => $user->hasRole(['driver']) ? $user->direction->id : $user->station->direction->id]
                );
            }
        }

        return $next($request);
    }
}
