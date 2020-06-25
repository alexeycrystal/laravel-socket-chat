<?php


namespace App\Modules\Realtime\Services;


use App\Generics\Services\AbstractService;
use App\Modules\Auth\Services\AuthServiceContract;
use App\Modules\Realtime\Repositories\UserRealtimeDependencyRepositoryContract;

/**
 * Class UserRealtimeDependencyService
 * @package App\Modules\Realtime\Services
 */
class UserRealtimeDependencyService extends AbstractService implements UserRealtimeDependencyServiceContract
{
    /**
     * @var AuthServiceContract
     */
    protected AuthServiceContract $authService;
    /**
     * @var UserRealtimeDependencyRepositoryContract
     */
    protected UserRealtimeDependencyRepositoryContract $realtimeDependencyRepository;

    /**
     * UserRealtimeDependencyService constructor.
     * @param AuthServiceContract $authService
     * @param UserRealtimeDependencyRepositoryContract $realtimeDependencyRepository
     */
    public function __construct(AuthServiceContract $authService,
                                UserRealtimeDependencyRepositoryContract $realtimeDependencyRepository)
    {
        $this->authService = $authService;
        $this->realtimeDependencyRepository = $realtimeDependencyRepository;
    }

    /**
     * @param array $usersIds
     * @return bool|null
     */
    public function addLoggedUserAsListener(array $usersIds): ?bool
    {
        $user = $this->authService->getLoggedUser();

        $created = $this->realtimeDependencyRepository
            ->storeUsersIdsToListen($user->id, $usersIds);

        if($created)
            return true;

        return false;
    }

    /**
     * @return bool|null
     */
    public function removeLoggedUserFromListeners(): ?bool
    {
        $user = $this->authService->getLoggedUser();

        $result = $this->realtimeDependencyRepository
            ->removeListenerFromGroups($user->id);

        if(isset($result))
            return $result;

        return null;
    }
}
