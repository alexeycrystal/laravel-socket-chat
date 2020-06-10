<?php


namespace App\Modules\Message\Requests;


use App\Generics\Requests\JsonRequest;
use App\Modules\Auth\Services\AuthServiceContract;
use App\Modules\Message\Rules\MessageExistedByUserValidationRule;
use App\Modules\Message\Services\MessageServiceContract;

class DestroyMessageRequest extends JsonRequest
{
    public function all($keys = null)
    {
        $data = parent::all();

        $data['id'] = $this->route('message');

        return $data;
    }

    public function rules(AuthServiceContract $authService,
                          MessageServiceContract $messageService)
    {
        return [
            'id' => [
                'required',
                'integer',
                new MessageExistedByUserValidationRule($authService, $messageService)
            ],
        ];
    }
}
