<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Stancl\Tenancy\Database\Models\Domain;
use Symfony\Component\HttpFoundation\Response;

class IdentifyTenant
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $domain = $request->header('subdomain') ?? $request->getHost();
        $tenant = Domain::where('domain', $domain)->first();

        if (!$tenant) {
            return $next($request);
        }

        tenancy()->initialize($tenant->tenant_id);
        return $next($request);
    }
}
