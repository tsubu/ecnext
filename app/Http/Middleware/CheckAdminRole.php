<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\Auth\AdminAuthService;

class CheckAdminRole
{
    public function __construct(
        protected AdminAuthService $authService
    ) {}

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $permission
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        if (!$this->authService->hasPermission($permission)) {
            abort(403, 'Unauthorized access to administrative area.');
        }

        return $next($request);
    }
}
