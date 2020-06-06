<?php


namespace App\Modules\User\Requests;


use App\Generics\Requests\JsonRequest;
use App\Modules\Auth\Services\AuthServiceContract;
use App\Modules\User\Rules\AvailableNicknameValidationRule;
use App\Modules\User\Rules\AvailableProfileLanguageValidationRule;
use App\Modules\User\Services\UserProfileServiceContract;

class UserProfileUpdateRequest extends JsonRequest
{
    public function rules(AuthServiceContract $authService,
                          UserProfileServiceContract $userProfileService)
    {
        return [
            'name' => [
                'required',
                'string',
                'min:1',
                'max:200',
            ],
            'lang' => [
                'required',
                'string',
                'size:2',
                new AvailableProfileLanguageValidationRule()
            ],
            'timezone' => [
                'required',
                'string',
                'timezone',
            ],
            'nickname' => [
                'sometimes',
                'string',
                'min:3',
                "max:200",
                new AvailableNicknameValidationRule($authService, $userProfileService)
            ],
            'phone' => [
                'sometimes',
                'string',
                'max:40',
            ],
        ];
    }
}
