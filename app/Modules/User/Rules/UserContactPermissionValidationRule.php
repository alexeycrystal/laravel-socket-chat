<?php


namespace App\Modules\User\Rules;


use App\Modules\Auth\Services\AuthServiceContract;
use App\Modules\User\Services\UserContactsServiceContract;
use Illuminate\Contracts\Validation\Rule;

class UserContactPermissionValidationRule implements Rule
{
    protected AuthServiceContract $authService;
    protected UserContactsServiceContract $userContactsService;


    public function __construct(AuthServiceContract $authService,
                                UserContactsServiceContract $userContactsService)
    {
        $this->authService = $authService;
        $this->userContactsService = $userContactsService;
    }

    public function passes($attribute, $value)
    {
        $user = $this->authService->getLoggedUser();

        $result = $this->userContactsService
            ->isContactExistsByUser($user->id, $value);

        if($result)
            return true;

        return false;
    }

    public function message()
    {
        return 'Contact does not exists or you don\'t have permission to edit this contact.';
    }
}
