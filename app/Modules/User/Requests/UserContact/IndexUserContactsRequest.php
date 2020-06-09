<?php


namespace App\Modules\User\Requests\UserContact;


use App\Generics\Requests\JsonRequest;

class IndexUserContactsRequest extends JsonRequest
{
    public function rules()
    {
        return [
            'page' => [
                'required',
                'integer',
                "min:1",
            ],
            'per_page' => [
                'required',
                'integer',
                'min:1',
            ],
        ];
    }
}
