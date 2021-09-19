<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class MobileAuthentication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        echo('Mobile authentication middleware.');

        return $next($request);
    }
}
