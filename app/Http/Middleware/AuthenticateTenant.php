<?php

namespace App\Http\Middleware;

use App\Services\TenantDatabaseConnector;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Closure;

class AuthenticateTenant
{
    public function __construct(public TenantDatabaseConnector $tenantDatabaseConnector)
    {
    }

    public function handle(Request $request, Closure $next)
    {
        $domain = explode('//', $request->fullUrl())[1];
        $subdomain = explode('.', $domain)[0];
        if (!$subdomain) {
            return new JsonResponse("The provided host url is invalid.");
        }
        $this->tenantDatabaseConnector->connect($subdomain);

        return $next($request);
    }
}