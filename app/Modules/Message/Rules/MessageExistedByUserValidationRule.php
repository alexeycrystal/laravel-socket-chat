<?php


namespace App\Modules\Message\Rules;


use App\Modules\Auth\Services\AuthServiceContract;
use App\Modules\Message\Services\MessageServiceContract;
use Illuminate\Contracts\Validation\Rule;

class MessageExistedByUserValidationRule implements Rule
{
    protected AuthServiceContract $authService;
    protected MessageServiceContract $messageService;

    public function __construct(AuthServiceContract $authService,
                                MessageServiceContract $messageService)
    {
        $this->authService = $authService;
        $this->messageService = $messageService;
    }

    public function passes($attribute, $value)
    {
        $user = $this->authService->getLoggedUser();

        $result = $this->messageService
            ->isMessageExistsByUser($user->id, $value);

        if($result)
            return true;

        return false;
    }

    public function message()
    {
        return 'Message does not exists or you don\'t have permission for it!';
    }
}
