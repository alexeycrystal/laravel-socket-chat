<?php


namespace App\Modules\Auth\Services;


use App\Facades\RepositoryManager;
use App\GenericModels\User;
use App\Generics\Services\AbstractService;
use App\Modules\User\Repositories\UserRepositoryContract;
use App\Modules\User\Repositories\UserSettingsRepositoryContract;
use App\Services\IP\LocationIPServiceContract;
use Illuminate\Support\Facades\Hash;

/**
 * Class AuthService
 * @package App\Modules\Auth\Services
 */
class AuthService extends AbstractService implements AuthServiceContract
{
    /**
     * @var User
     */
    protected User $loggedUser;
    /**
     * @var UserRepositoryContract
     */
    protected UserRepositoryContract $userRepository;
    /**
     * @var JWTServiceContract
     */
    protected JWTServiceContract $jwtService;
    /**
     * @var UserSettingsRepositoryContract
     */
    protected UserSettingsRepositoryContract $userSettingsRepository;
    /**
     * @var LocationIPServiceContract
     */
    protected LocationIPServiceContract $locationIPService;

    /**
     * AuthService constructor.
     * @param UserRepositoryContract $userRepository
     * @param JWTServiceContract $jwtService
     * @param UserSettingsRepositoryContract $userSettingsRepository
     * @param LocationIPServiceContract $locationIPService
     */
    public function __construct(UserRepositoryContract $userRepository,
                                JWTServiceContract $jwtService,
                                UserSettingsRepositoryContract $userSettingsRepository,
                                LocationIPServiceContract $locationIPService)
    {
        $this->userRepository = $userRepository;
        $this->jwtService = $jwtService;
        $this->userSettingsRepository = $userSettingsRepository;
        $this->locationIPService = $locationIPService;
    }

    /**
     * @param array $credentials
     * @return array|null
     */
    public function login(array $credentials): ?array
    {
        $credentials = [
            'email' => $credentials['email'],
            'password' => $credentials['password'],
        ];

        $token = $this->jwtService
            ->createTokenByCredentials($credentials);

        if(!$token || $this->jwtService->hasErrors()) {

            $this->addError(
                422,
                'AuthService@login',
                'Your password is invalid or user with such password is not existed!'
            );
            return null;
        }

        return [
            'token' => $token,
        ];
    }

    /**
     * @param array $payload
     * @return array|null
     */
    public function registration(array $payload): ?array
    {
        $fields = [
            'email' => $payload['email'],
            'password' => Hash::make($payload['password']),
            'name' => $payload['name'],
        ];

        $user = RepositoryManager::resolveTransactional(function() use ($fields, $payload) {

            $user = $this->userRepository
                ->create($fields);

            $settings = null;

            if($user) {

                $settingsPayload = [
                    'user_id' => $user->id,
                    'lang' => $payload['lang'],
                ];

                $settingsData = $this->prepareUserSettingsPayload($settingsPayload);

                $settings = $this->userSettingsRepository
                    ->create($settingsData);
            }

            if(!$user || !$settings)
                return null;

            return $user;

        }, true);


        if(!$user) {

            $this->addError(
                502,
                'AuthService@registration',
                'Error occurs while creating the user! Try again.'
            );
            return null;
        }

        $token = $this->jwtService
            ->createTokenByUser($user);

        if(!$token || $this->jwtService->hasErrors()) {

            $this->addError(
                400,
                'AuthService@registration',
                'JWT token creation failed!'
            );
            return null;
        }

        return [
            'token' => $token,
            'user_id' => $user->id,
        ];
    }

    /**
     * @param array $payload
     * @return array|null
     */
    protected function prepareUserSettingsPayload(array $payload): ?array
    {
        $requestIp = request()->ip();

        $data = $this->locationIPService
            ->getLocationDataByIp($requestIp);

        return [
            'user_id' => $payload['user_id'],
            'lang' => strtolower($payload['lang']),

            'country' => $data->countryName ?? 'Ukraine',
            'region' => $data->regionName ?? 'Kyiv',
            'city' => $data->cityName ?? 'Kyiv',
            'latitude' => $data->latitude ?? '50.4403',
            'longitude' => $data->latitude ?? '30.4487',
            'timezone' => $data->timezone ?? 'Europe/Kiev',
        ];
    }

    /**
     * @return User|null
     */
    public function getLoggedUser(): ?User
    {
        $user = null;

        if(isset($this->loggedUser))
            return $this->loggedUser;

        $user = $this->jwtService
            ->getLoggedUserByToken();

        if($user) {

            $this->loggedUser = $user;

            return $user;
        }

        return null;
    }

    /**
     * Set new User instance for php LifeCycle.
     *
     * @param int $userID
     * @return bool
     */
    public function setLoggedUser(int $userID): bool
    {
        $user = $this->userRepository
            ->get($userID);

        if ($user) {

            $this->loggedUser = $user;

            return true;
        }

        return false;
    }
}
