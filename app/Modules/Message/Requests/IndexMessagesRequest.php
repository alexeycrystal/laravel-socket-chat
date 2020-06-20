<?php


namespace App\Modules\Message\Requests;


use App\Generics\Requests\JsonRequest;
use App\Modules\Auth\Services\AuthServiceContract;
use App\Modules\Chat\Rules\UserChatPermissionValidationRule;
use App\Modules\Chat\Services\ChatServiceContract;

class IndexMessagesRequest extends JsonRequest
{
    public function rules(AuthServiceContract $authService,
                          ChatServiceContract $chatService)
    {
        return [
            'chat_id' => [
                'sometimes',
                'integer',
                new UserChatPermissionValidationRule($authService, $chatService),
            ],
            'page' => [
                'required',
                'integer',
                "min:1",
            ],
            'per_page' => [
                'required',
                'integer',
                'min:1',
            ],
        ];
    }
}
