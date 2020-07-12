<?php


namespace App\Modules\Auth\Services;


use App\Entities\Request\LoginEntity;
use App\Entities\Response\Login\LoginResultEntity;
use App\Entities\Response\LoginResponseEntity;
use App\GenericModels\User;
use App\Generics\Services\AbstractServiceContract;

/**
 * Interface AuthServiceContract
 * @package App\Modules\Auth\Services
 */
interface AuthServiceContract extends AbstractServiceContract
{
    /**
     * @param LoginEntity $entity
     * @return LoginResultEntity|null
     */
    public function login(LoginEntity $entity): ?LoginResultEntity;

    /**
     * @param array $payload
     * @return array|null
     */
    public function registration(array $payload): ?array;

    /**
     * @return User|null
     */
    public function getLoggedUser(): ?User;

    /**
     * @param int $userID
     * @return bool
     */
    public function setLoggedUser(int $userID): bool;
}
