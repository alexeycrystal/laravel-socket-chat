<?php


namespace App\Modules\Realtime\Requests;


use App\Generics\Requests\JsonRequest;
use App\Modules\Auth\Services\AuthServiceContract;
use App\Modules\Chat\Services\ChatServiceContract;
use App\Modules\Realtime\Rules\UserHasAccessToChatsRule;

class StoreUserRealtimeDependencyRequest extends JsonRequest
{
    public function rules(AuthServiceContract $authService,
                          ChatServiceContract $chatService)
    {
        return [
            'chats_ids' => [
                'required',
                'array',
                'min:1',
                new UserHasAccessToChatsRule($authService, $chatService)
            ],
        ];
    }
}
