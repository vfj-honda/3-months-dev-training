<?php

namespace App\Http\Middleware;

use Closure;

class AuthorityError
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
        return auth()->user()->authority != 1 ? redirect(route('user.root')) : $next($request);
    }
}
