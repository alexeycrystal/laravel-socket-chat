<?php


namespace App\Modules\Auth\Services;


use App\Generics\Services\AbstractService;
use Tymon\JWTAuth\Exceptions\JWTException;
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
}
