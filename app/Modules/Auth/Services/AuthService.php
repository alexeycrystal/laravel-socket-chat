<?php


namespace App\Modules\Auth\Services;


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

        if($this->jwtService->hasErrors()) {

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
}
