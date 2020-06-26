<?php


namespace App\Modules\Realtime\Rules;


use App\Modules\Auth\Services\AuthServiceContract;
use App\Modules\Chat\Services\ChatServiceContract;
use Illuminate\Contracts\Validation\Rule;

class UserHasAccessToChatsRule implements Rule
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
            ->isUserHasAccessToChats($user->id, $value);

        if($result)
            return true;

        return false;
    }

    public function message()
    {
        return 'User has no access to one of the specified chats. Try again.';
    }
}
