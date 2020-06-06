<?php


namespace App\Modules\User\Rules;


use App\Modules\Auth\Services\AuthServiceContract;
use App\Modules\Auth\Services\JWTServiceContract;
use Illuminate\Contracts\Validation\Rule;

class OldPasswordValidationRule implements Rule
{
    protected AuthServiceContract $authService;
    protected JWTServiceContract $jwtService;

    public function __construct(AuthServiceContract $authService,
                                JWTServiceContract $jwtService)
    {
        $this->authService = $authService;
        $this->jwtService = $jwtService;
    }

    public function passes($attribute, $value)
    {
        $user = $this->authService->getLoggedUser();

        $email = $user->email;

        $credentials = [
            'email' => $email,
            'password' => $value,
        ];

        $token = $this->jwtService
            ->createTokenByCredentials($credentials);

        if($token)
            return true;

        return false;
    }

    public function message()
    {
        return 'Old password does not match!';
    }
}
