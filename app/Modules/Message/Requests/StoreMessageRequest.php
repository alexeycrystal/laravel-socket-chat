<?php


namespace App\Modules\Message\Requests;


use App\Generics\Requests\JsonRequest;
use App\Modules\Auth\Services\AuthServiceContract;
use App\Modules\Chat\Rules\UserChatPermissionValidationRule;
use App\Modules\Chat\Services\ChatServiceContract;

class StoreMessageRequest extends JsonRequest
{
    public function rules(AuthServiceContract $authService,
                          ChatServiceContract $chatService)
    {
        return [
            'chat_id' => [
                'required',
                'integer',
                new UserChatPermissionValidationRule($authService, $chatService),
            ],
            'text' => [
                'required',
                'string',
                'min:1',
                'max:2000',
            ],
        ];
    }
}
