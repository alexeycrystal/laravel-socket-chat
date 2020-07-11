<?php


namespace App\Modules\User\Requests;


use App\Generics\Requests\JsonRequest;

class SaveProfilePhotoRequest extends JsonRequest
{
    public function rules()
    {
        return [
            'photo' => [
                'required',
                'image',
                'mimes:jpeg,jpg,png',
                'max:3000',
            ]
        ];
    }
}
