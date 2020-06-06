<?php


namespace App\Modules\User\Services;


use App\Generics\Services\AbstractService;
use App\Modules\Auth\Services\AuthServiceContract;
use App\Modules\User\Repositories\UserRepositoryContract;
use App\Modules\User\Repositories\UserSettingsRepositoryContract;
use App\Modules\User\Transformers\UserProfileTransformer;
use Illuminate\Support\Facades\Hash;

/**
 * Class UserProfileService
 * @package App\Modules\User\Services
 */
class UserProfileService extends AbstractService implements UserProfileServiceContract
{
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
     * UserProfileService constructor.
     * @param AuthServiceContract $authService
     * @param UserSettingsRepositoryContract $userSettingsRepository
     * @param UserRepositoryContract $userRepository
     */
    public function __construct(AuthServiceContract $authService,
                                UserSettingsRepositoryContract $userSettingsRepository,
                                UserRepositoryContract $userRepository)
    {
        $this->authService = $authService;
        $this->userSettingsRepository = $userSettingsRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @return array|null
     */
    public function getUserProfileInfoByLoggedUser(): ?array
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

        return UserProfileTransformer::transform($settings);
    }

    /**
     * @param string $password
     * @return array|array[]|null
     */
    public function changePassword(string $password): ?array
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

        return [
            'result' => $result
        ];
    }
}
