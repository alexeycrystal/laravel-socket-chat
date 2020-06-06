<?php


namespace App\Modules\User\Requests;


use App\Generics\Requests\JsonRequest;
use App\Modules\Auth\Services\AuthServiceContract;
use App\Modules\Auth\Services\JWTServiceContract;
use App\Modules\User\Rules\OldPasswordValidationRule;

class ChangePasswordRequest extends JsonRequest
{
    public function rules(AuthServiceContract $authService,
                          JWTServiceContract $JWTService)
    {
        return [
            'old_password' => [
                'required',
                'string',
                new OldPasswordValidationRule($authService, $JWTService)
            ],
            'password' => [
                'required',
                'string',
                'min:6',
                "max:100",
                'confirmed',
            ],
            'password_confirmation' => [
                'required',
                'string',
                'min:6',
            ]
        ];
    }
}
