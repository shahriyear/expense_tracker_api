<?php

namespace App\Http\Middleware;

use Closure;
use App\User;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;

class JwtMiddleware
{
    public function handle($request, Closure $next, $guard = null)
    {

        if (!$bearer = $request->header('Authorization')) {
            return response401('Unauthorized!');
        }


        if (!$token = $this->bearer($bearer)) {
            return response401('Token Mismatched!');
        }


        try {
            $credentials = JWT::decode($token, env('JWT_SECRET'), ['HS256']);
        } catch (ExpiredException $e) {
            return response401('Provided token is expired.');
        } catch (Exception $e) {
            return response401('Unauthorized!');
        }

        if (!($user = User::findId($credentials->sub))) {
            return response401('Unauthorized!');
        }

        $request->auth = $user;

        return $next($request);
    }


    private function bearer($bearer)
    {
        $bearer = preg_replace('!\s+!', ' ', ucfirst($bearer));
        preg_match('/Bearer\s(\S+)/', $bearer, $matches);
        return $matches[1] ?? false;
    }
}
