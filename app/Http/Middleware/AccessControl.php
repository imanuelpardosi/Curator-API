<?php
namespace App\Http\Middleware;

use Closure;
use App\Repositories\AccessTokenRepository;

class AccessControl
{
    protected $access_token;

    public function __construct(AccessTokenRepository $access_token)
    {
        $this->access_token = $access_token;
    }

    public function handle($request, Closure $next)
    {
        if ($request->is('auth/login'))
            return $next($request);

        if (!empty($token = $request->header('Authorization')))

            if (!empty($this->access_token->findToken($token))) {
                return $next($request);
            }

        return response()->json(['error' => 'Not Authorized'], 403);

    }
}
