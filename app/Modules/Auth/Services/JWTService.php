<?php


namespace App\Modules\Auth\Services;


use App\GenericModels\User;
use App\Generics\Services\AbstractService;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * Class JWTService
 * @package App\Modules\Auth\Services
 */
class JWTService extends AbstractService implements JWTServiceContract
{
    /**
     * @param array $credentials
     * @return string|null
     */
    public function createTokenByCredentials(array $credentials): ?string
    {
        try {

            if (!$token = JWTAuth::attempt($credentials))
                return null;

            return $token;
        } catch (JWTException $e) {

            $this->addError(
                $e->getCode(),
                'JWTService@createTokenByCredentials',
                $e->getMessage()
            );
        }

        return null;
    }

    /**
     * @return User|null
     */
    public function getLoggedUserByToken(): ?User
    {
        $errorLevel = 'JWTService@getLoggedUserByToken';

        $user = null;

        try {

            if (! $user = JWTAuth::parseToken()->authenticate())
                $this->addError(404, $errorLevel, 'user_not_found');

            return $user;

        } catch (TokenExpiredException $e) {

            $this->addError(403, $errorLevel, 'token_expired');

        } catch (TokenInvalidException $e) {

            $this->addError(403, $errorLevel, 'token_invalid');

        } catch (JWTException $e) {
            $this->addError(400, $errorLevel, 'token_absent');
        }

        return null;
    }
}
