<?php


namespace App\Modules\User\Requests\UserContact;


use App\Generics\Requests\JsonRequest;

class UserContactStoreRequest extends JsonRequest
{
    public function rules()
    {
        return [
            'contact_user_id' => [
                'required',
                'integer',
                'exists:users,id',
            ],
        ];
    }
}
