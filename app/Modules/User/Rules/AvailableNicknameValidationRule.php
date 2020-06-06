<?php


namespace App\Modules\User\Rules;


use App\Modules\Auth\Services\AuthServiceContract;
use App\Modules\User\Services\UserProfileServiceContract;
use Illuminate\Contracts\Validation\Rule;

class AvailableNicknameValidationRule implements Rule
{
    protected AuthServiceContract $authService;
    protected UserProfileServiceContract $userProfileService;

    public function __construct(AuthServiceContract $authService,
                                UserProfileServiceContract $userProfileService)
    {
        $this->authService = $authService;
        $this->userProfileService = $userProfileService;
    }

    public function passes($attribute, $value)
    {
        $user = $this->authService->getLoggedUser();

        $isExists = $this->userProfileService
            ->isNicknameAlreadyTaken($user->id, $value);

        if($isExists)
            return false;

        return true;
    }

    public function message()
    {
        return 'Nickname is already is already taken. Choose another one.';
    }
}
