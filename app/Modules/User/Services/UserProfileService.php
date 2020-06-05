<?php


namespace App\Modules\User\Services;


use App\Generics\Services\AbstractService;
use App\Modules\Auth\Services\AuthServiceContract;
use App\Modules\User\Repositories\UserSettingsRepositoryContract;
use App\Modules\User\Transformers\UserProfileTransformer;

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
     * UserProfileService constructor.
     * @param AuthServiceContract $authService
     * @param UserSettingsRepositoryContract $userSettingsRepository
     */
    public function __construct(AuthServiceContract $authService,
                                UserSettingsRepositoryContract $userSettingsRepository)
    {
        $this->authService = $authService;
        $this->userSettingsRepository = $userSettingsRepository;
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
}
