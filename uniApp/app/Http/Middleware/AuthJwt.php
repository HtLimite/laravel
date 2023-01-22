<?php
namespace App\Http\Middleware;
use Closure;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

use Exception;
class AuthJwt
{
    protected $auth;
    public function __construct(JWTAuth $auth)
    {
        $this->auth = $auth;
    }

    public function handle($request, Closure $next)
    {
        try {
            if (! $this->auth->parseToken()->authenticate()) {
                throw new UnauthorizedHttpException('jwt-auth', 'User not found');
            }
        } catch (JWTException $e) {
            throw new UnauthorizedHttpException('jwt-auth', $e->getMessage(), $e, $e->getCode());
        }
        return $next($request);
    }
}
