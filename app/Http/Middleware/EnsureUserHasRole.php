<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;

class EnsureUserHasRole {
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next, string $role) {
        if ($request->user()->hasRole($role))
            return $next($request);
        throw new HttpResponseException(response()->json(json_encode([
            'message' => "The user {$request->user()->name} can't access to this endpoint"]), \Illuminate\Http\Response::HTTP_FORBIDDEN));
    }
}
