<?php


namespace App\Http\Middleware\Auth;


use Closure;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JWTTokenCheck extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     *
     * @return mixed
     * @throws \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException
     *
     */
    public function handle($request, Closure $next)
    {
        $this->checkForToken($request);

        try {

            $token = $this->auth->parseToken();

            if (!$token->authenticate())
                throw new UnauthorizedHttpException('jwt-auth', 'User not found');

            return $next($request);

        } catch(TokenExpiredException $e) {

            $refreshedToken  = $token->refresh();

        } catch(JWTException $e) {

            throw new UnauthorizedHttpException(
                'jwt-auth', $e->getMessage(), $e, $e->getCode()
            );
        }

        $response = $next($request);

        if($refreshedToken)
            $response->header('Access-Control-Expose-Headers', 'X-JWT-Refresh')
                ->header('X-JWT-Refresh', $refreshedToken);

        return $response;
    }
}
