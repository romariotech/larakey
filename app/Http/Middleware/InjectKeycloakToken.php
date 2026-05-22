<?php

namespace App\Http\Middleware;

use App\Services\KeycloakAuthService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class InjectKeycloakToken
{
    public function __construct(
        protected KeycloakAuthService $keycloakService
    ) {
    }

    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->bearerToken() && $request->session()->has('access_token')) {
            $token = $request->session()->get('access_token');

            $payload = json_decode(base64_decode(explode('.', $token)[1] ?? ''));

            if ($payload && isset($payload->exp) && time() >= ($payload->exp - 10)) {

                $newTokens = $this->keycloakService->refreshAccessToken(
                    $request->session()->get('refresh_token')
                );

                if ($newTokens) {
                    $token = $newTokens['access_token'];
                } else {

                    $request->session()->flush();

                    Auth::logout();

                    return response()->json(['message' => 'Session expired, please login again.'], 401);
                }
            }

            $request->headers->set('Authorization', "Bearer $token");
        }

        return $next($request);
    }
}
