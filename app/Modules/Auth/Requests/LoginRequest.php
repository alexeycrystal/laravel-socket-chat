<?php


namespace App\Modules\Auth\Requests;


use App\Generics\Requests\JsonRequest;

class LoginRequest extends JsonRequest
{
    public function rules()
    {
        return [
            'email' => [
                'required',
                'email',
                'exists:users,email',
            ],
            'password' => [
                'required',
                'string',
                'min:6',
                'max:100',
            ],
        ];
    }
}
