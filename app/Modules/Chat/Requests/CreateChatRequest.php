<?php


namespace App\Modules\Chat\Requests;


use App\Generics\Requests\JsonRequest;

class CreateChatRequest extends JsonRequest
{
    public function rules()
    {
        return [
            'users_ids' => [
                'required',
                'array',
                'min:1',
            ],
            "users_ids.*"  => [
                'required',
                'integer',
                'distinct',
                'exists:users,id'
            ],
        ];
    }
}
