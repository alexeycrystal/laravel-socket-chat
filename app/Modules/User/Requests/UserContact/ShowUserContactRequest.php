<?php


namespace App\Modules\User\Requests\UserContact;


use App\Generics\Requests\JsonRequest;
use App\Modules\Auth\Services\AuthServiceContract;
use App\Modules\User\Rules\UserContactPermissionValidationRule;
use App\Modules\User\Services\UserContactsServiceContract;

class ShowUserContactRequest extends JsonRequest
{
    public function all($keys = null)
    {
        $data = parent::all();

        $data['id'] = $this->route('contact');

        return $data;
    }

    public function rules(AuthServiceContract $authService,
                          UserContactsServiceContract $userContactsService)
    {
        return [
            'id' => [
                'required',
                'integer',
                new UserContactPermissionValidationRule($authService, $userContactsService)
            ]
        ];
    }
}
