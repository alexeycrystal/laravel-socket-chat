<?php


namespace App\Http\Middleware\Auth;


use App\Generics\Entities\UnauthorizedResponseEntity;
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

        try {

            $this->checkForToken($request);

            $token = $this->auth->parseToken();

            if (!$token->authenticate())
                throw new UnauthorizedHttpException('jwt-auth', 'User not found');

            return $next($request);

        } catch(TokenExpiredException $e) {

            $refreshedToken  = $token->refresh();

        } catch(UnauthorizedHttpException $e) {

            $status = 401;

            return response()
                ->json(new UnauthorizedResponseEntity([
                    'message' => 'You are not authorized for this action.',
                    'status_code' => $status
                ]), $status);
        } catch(JWTException $e) {

            $status = 401;

            return response()
                ->json(new UnauthorizedResponseEntity([
                    'message' => 'You are not authorized for this action.',
                    'status_code' => $status
                ]), $status);
        }

        $response = $next($request);

        if($refreshedToken)
            $response->header('Access-Control-Expose-Headers', 'X-JWT-Refresh')
                ->header('X-JWT-Refresh', $refreshedToken);

        return $response;
    }
}
