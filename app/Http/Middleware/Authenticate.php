<?php

namespace App\Http\Middleware;

use App\Models\HusmusenError;
use App\Models\HusmusenUser;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, \Closure $next, string $required_permission): Response
    {
        $token = $request->header('Husmusen-Access-Token');
        if (!$token) {
            return HusmusenError::create(401, 'ERR_NO_TOKEN_PROVIDED', "You need to provide your access token in the header 'Husmusen-Access-Token'!");
        }

        $is_valid = HusmusenUser::check_token_validity($token);
        if (!$is_valid) {
            return HusmusenError::create(401, 'ERR_INVALID_TOKEN', 'It seems as if your access token is invalid!');
        }

        $token_info = HusmusenUser::decode_token($token);

        if ('admin' === $required_permission) {
            if (!$token_info->admin) {
                return HusmusenError::create(401, 'ERR_NOT_ALLOWED', 'You need to be an admin to do this!');
            }
        }

        $request->attributes->add(['auth_username' => $token_info->sub]);
        $request->attributes->add(['auth_is_admin' => $token_info->admin]);

        return $next($request);
    }
}
