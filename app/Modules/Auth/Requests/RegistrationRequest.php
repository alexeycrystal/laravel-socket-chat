<?php


namespace App\Modules\Auth\Requests;


use App\Generics\Requests\JsonRequest;

class RegistrationRequest extends JsonRequest
{
    public function rules()
    {
        return [
            'email' => [
                'required',
                'email',
                'unique:users,email'
            ],
            'password' => [
                'required',
                'string',
                'min:6',
                'max:100',
                'confirmed',
            ],
            'password_confirmation' => [
                'required',
                'min:6',
                'max:100',
            ],
            'name' => [
                'required',
                'string',
                'max:200',
            ],
        ];
    }
}
