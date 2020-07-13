<?php


namespace App\Modules\Auth\Services;


use App\Entities\Request\Login\LoginEntity;
use App\Entities\Request\Registration\RegistrationEntity;
use App\Entities\Response\Login\LoginResultEntity;
use App\Entities\Response\Registration\RegistrationResultEntity;
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
     * @param RegistrationEntity $payload
     * @return RegistrationResultEntity|null
     */
    public function registration(RegistrationEntity $payload): ?RegistrationResultEntity;

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
