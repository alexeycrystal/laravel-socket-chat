<?php


namespace App\Modules\Chat\Rules;


use App\Modules\Auth\Services\AuthServiceContract;
use App\Modules\Chat\Services\ChatServiceContract;
use Illuminate\Contracts\Validation\Rule;

class UserChatPermissionValidationRule implements Rule
{
    protected AuthServiceContract $authService;
    protected ChatServiceContract $chatService;

    public function __construct(AuthServiceContract $authService,
                                ChatServiceContract $chatService)
    {
        $this->authService = $authService;
        $this->chatService = $chatService;
    }

    public function passes($attribute, $value)
    {
        $user = $this->authService->getLoggedUser();

        $result = $this->chatService
            ->isUserExistsByChat($user->id, $value);

        if($result)
            return $result;

        return null;
    }

    public function message()
    {
        return 'Chat does not exists or you don\'t have permission for it!';
    }
}
