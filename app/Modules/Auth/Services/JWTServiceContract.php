<?php


namespace App\Modules\Auth\Services;


use App\GenericModels\User;
use App\Generics\Services\AbstractServiceContract;

/**
 * Interface JWTServiceContract
 * @package App\Modules\Auth\Services
 */
interface JWTServiceContract extends AbstractServiceContract
{
    /**
     * Create JWT token by chosen credentials
     *
     * @param array $credentials
     * @return string|null
     */
    public function createTokenByCredentials(array $credentials): ?string;

    /**
     * @return User|null
     */
    public function getLoggedUserByToken(): ?User;
}
