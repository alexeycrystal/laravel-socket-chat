<?php


namespace App\Modules\Auth\Services;


use App\Generics\Services\AbstractServiceContract;

/**
 * Interface AuthServiceContract
 * @package App\Modules\Auth\Services
 */
interface AuthServiceContract extends AbstractServiceContract
{
    /**
     * @param array $credentials
     * @return array|null
     */
    public function login(array $credentials): ?array;

    /**
     * @param array $payload
     * @return array|null
     */
    public function registration(array $payload): ?array;
}
