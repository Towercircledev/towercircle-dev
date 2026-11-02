<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/** 301 www.towercircle.in/* → towercircle.in/* so Google sees one domain. */
class CanonicalHost
{
    public function handle(Request $request, Closure $next)
    {
        $host = $request->getHost();
        if (str_starts_with($host, 'www.') && app()->isProduction()) {
            return redirect()->to($request->getScheme().'://'.substr($host, 4).$request->getRequestUri(), 301);
        }

        return $next($request);
    }
}
