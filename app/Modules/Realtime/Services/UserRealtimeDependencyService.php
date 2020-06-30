<?php


namespace App\Modules\Realtime\Services;


use App\Generics\Services\AbstractService;
use App\Modules\Auth\Services\AuthServiceContract;
use App\Modules\Chat\Repositories\ChatUserRepositoryContract;
use App\Modules\Realtime\Repositories\UserRealtimeDependencyRepositoryContract;
use Illuminate\Support\Facades\Log;

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

    protected ChatUserRepositoryContract $chatUserRepository;

    /**
     * UserRealtimeDependencyService constructor.
     * @param AuthServiceContract $authService
     * @param UserRealtimeDependencyRepositoryContract $realtimeDependencyRepository
     * @param ChatUserRepositoryContract $chatUserRepository
     */
    public function __construct(AuthServiceContract $authService,
                                UserRealtimeDependencyRepositoryContract $realtimeDependencyRepository,
                                ChatUserRepositoryContract $chatUserRepository)
    {
        $this->authService = $authService;
        $this->realtimeDependencyRepository = $realtimeDependencyRepository;
        $this->chatUserRepository = $chatUserRepository;
    }

    /**
     * @param array $chatsIds
     * @return bool|null
     */
    public function addLoggedUserAsListener(array $chatsIds): ?bool
    {
        $user = $this->authService->getLoggedUser();

        $userIds = $this->chatUserRepository
            ->getAllUsersByChats($chatsIds, [$user->id]);

        if($userIds) {

            $userIds = $userIds->pluck('user_id')
                ->values()
                ->toArray();

            $created = $this->realtimeDependencyRepository
                ->storeUsersIdsToListen($user->id, $userIds);

            return true;
        }

        $this->addError(
            504,
            'UserRealtimeDependencyService@addLoggedUserAsListener',
            'Some serious error occurs during the ws-listeners save process.'
        );
        return null;
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

        $this->addError(
            504,
            'UserRealtimeDependencyService@removeLoggedUserFromListeners',
            'Some serious error occurs during the user-ws-listeners delete process.'
        );
        return null;
    }
}
