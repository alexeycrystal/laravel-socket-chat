<?php


namespace App\Modules\User\Services;


use App\Facades\RepositoryManager;
use App\Generics\Services\AbstractService;
use App\Modules\Auth\Services\AuthServiceContract;
use App\Modules\Realtime\Events\UserStatusChangedEvent;
use App\Modules\Realtime\Repositories\UserRealtimeDependencyRepositoryContract;
use App\Modules\User\Repositories\UserCacheRepositoryContract;
use App\Modules\User\Repositories\UserRepositoryContract;
use App\Modules\User\Repositories\UserSettingsRepositoryContract;
use App\Traits\Broadcast\PostProcessingBroadcaster;
use Illuminate\Support\Facades\Hash;
use stdClass;

/**
 * Class UserProfileService
 * @package App\Modules\User\Services
 */
class UserProfileService extends AbstractService implements UserProfileServiceContract
{
    use PostProcessingBroadcaster;

    /**
     * @var AuthServiceContract
     */
    protected AuthServiceContract $authService;
    /**
     * @var UserSettingsRepositoryContract
     */
    protected UserSettingsRepositoryContract $userSettingsRepository;
    /**
     * @var UserRepositoryContract
     */
    protected UserRepositoryContract $userRepository;
    /**
     * @var UserCacheRepositoryContract
     */
    protected UserCacheRepositoryContract $userCacheRepository;

    protected UserRealtimeDependencyRepositoryContract $userRealtimeDependencyRepository;

    /**
     * UserProfileService constructor.
     * @param AuthServiceContract $authService
     * @param UserSettingsRepositoryContract $userSettingsRepository
     * @param UserRepositoryContract $userRepository
     * @param UserCacheRepositoryContract $userCacheRepository
     * @param UserRealtimeDependencyRepositoryContract $userRealtimeDependencyRepository
     */
    public function __construct(AuthServiceContract $authService,
                                UserSettingsRepositoryContract $userSettingsRepository,
                                UserRepositoryContract $userRepository,
                                UserCacheRepositoryContract $userCacheRepository,
                                UserRealtimeDependencyRepositoryContract $userRealtimeDependencyRepository)
    {
        $this->authService = $authService;
        $this->userSettingsRepository = $userSettingsRepository;
        $this->userRepository = $userRepository;
        $this->userCacheRepository = $userCacheRepository;
        $this->userRealtimeDependencyRepository = $userRealtimeDependencyRepository;
    }

    /**
     * @return stdClass|null
     */
    public function getUserProfileInfoByLoggedUser(): ?\stdClass
    {
        $user = $this->authService->getLoggedUser();

        $settings = $this->userSettingsRepository
            ->get($user->id);

        if(!$settings) {

            $this->addError(
                504,
                'AuthService@getUserProfileInfoByLoggedUser',
                'Some temporary error happened with the database server.'
            );
            return null;
        }

        return $settings;
    }

    /**
     * @param string $password
     * @return array|array[]|null
     */
    public function changePassword(string $password): ?bool
    {
        $user = $this->authService
            ->getLoggedUser();

        $data = [
            'password' => Hash::make($password)
        ];

        $result = $this->userRepository
            ->update($user->id, $data);

        if(!isset($result)) {

            $this->addError(
                504,
                'UserProfileService@changePassword',
                'Some temporary error happened with the database server.'
            );
            return null;
        }

        return true;
    }

    /**
     * @param array $payload
     * @return bool|null
     */
    public function updateProfileSettings(array $payload): ?bool
    {
        $user = $this->authService->getLoggedUser();

        $result = RepositoryManager::resolveTransactional(function() use ($user, $payload) {

            $userPayload = [
                'name' => $payload['name'],
            ];

            $userId = $user->id;

            $userUpdated = $this->userRepository
                ->update($userId, $userPayload);

            $userSettingsPayload = getExcludedArrayByKeys($payload, $userPayload);

            $userSettingsUpdated = $this->userSettingsRepository
                ->update($userId, $userSettingsPayload);

            if(!$userUpdated
                || !$userSettingsUpdated)
                return null;

            return true;
        });

        if(isset($result))
            return $result;

        $this->addError(
            504,
            'UserProfileService@isNicknameAvailable',
            'Some temporary error happened with the database server.'
        );
        return null;
    }

    /**
     * @param int $userId
     * @param string $nickname
     * @return bool|null
     */
    public function isNicknameAlreadyTaken(int $userId, string $nickname): ?bool
    {
        $result = $this->userSettingsRepository
            ->isNicknameAlreadyTaken($userId, $nickname);

        if(isset($result))
            return $result;

        $this->addError(
            504,
            'UserProfileService@isNicknameAvailable',
            'Some temporary error happened with the database server.'
        );

        return null;
    }

    /**
     * @param string $status
     * @return bool|null
     */
    public function changeLoggedUserStatus(string $status): ?bool
    {
        $user = $this->authService->getLoggedUser();

        $result = $this->userCacheRepository
            ->updateStatus($user->id, $status);

        if($result) {

            $listenersUsersIds = $this->userRealtimeDependencyRepository
                ->getAllListenersByUser($user->id);

            $this->preparePostBroadcastData(
                'UserStatusChangedEvent',
                'chat.user', $user->id,
                ['data' => ['user_id' => $user->id, 'status' => $status]]
            );

            return true;
        }

        return false;
    }
}
