<?php


namespace App\Modules\Auth\Requests;


use App\Generics\Requests\JsonRequest;
use App\Modules\Auth\Rules\LanguageValidationRule;

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
            'lang' => [
                'required',
                'size:2',
                new LanguageValidationRule(),
            ]
        ];
    }
}
