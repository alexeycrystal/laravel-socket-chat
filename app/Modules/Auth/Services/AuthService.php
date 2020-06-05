<?php


namespace App\Modules\Auth\Services;


use App\GenericModels\User;
use App\Generics\Services\AbstractService;
use App\Modules\User\Repositories\UserRepositoryContract;
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
     * AuthService constructor.
     * @param UserRepositoryContract $userRepository
     * @param JWTServiceContract $jwtService
     */
    public function __construct(UserRepositoryContract $userRepository,
                                JWTServiceContract $jwtService)
    {
        $this->userRepository = $userRepository;
        $this->jwtService = $jwtService;
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
                400,
                'AuthService@registration',
                'JWT token creation failed!'
            );
            return null;
        }

        return [
            'data' => [
                'token' => $token,
            ]
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

        $user = $this->userRepository
            ->create($fields);

        if(!$user) {

            $this->addError(
                502,
                'AuthService@registration',
                'Error occurs while creating the user! Try again.'
            );
            return null;
        }

        $token = $this->jwtService
            ->createTokenByCredentials($payload);

        if(!$token || $this->jwtService->hasErrors()) {

            $this->addError(
                400,
                'AuthService@registration',
                'JWT token creation failed!'
            );
            return null;
        }

        return [
            'data' => [
                'token' => $token,
            ]
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
