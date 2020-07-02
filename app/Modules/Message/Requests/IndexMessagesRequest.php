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
                'required',
                'integer',
                new UserChatPermissionValidationRule($authService, $chatService),
            ],
            'page' => [
                'required_without:message_id',
                'integer',
                "min:1",
            ],
            'message_id' => [
                'required_without:page',
                'integer',
                'exists:messages,id',
            ],
            'per_page' => [
                'required',
                'integer',
                'min:1',
            ],
        ];
    }
}
