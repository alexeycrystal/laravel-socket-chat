<?php


namespace App\Modules\Chat\Requests;


use App\Generics\Requests\JsonRequest;
use App\Modules\Auth\Services\AuthServiceContract;
use App\Modules\Chat\Rules\UserChatPermissionValidationRule;
use App\Modules\Chat\Services\ChatServiceContract;

class ShowChatRequest extends JsonRequest
{
    public function all($keys = null)
    {
        $data = parent::all();

        $data['id'] = $this->route('chat');

        return $data;
    }

    public function rules(AuthServiceContract $authService,
                          ChatServiceContract $chatService)
    {
        return [
            'id' => [
                'required',
                'integer',
                new UserChatPermissionValidationRule($authService, $chatService),
            ]
        ];
    }
}
