<?php


namespace App\Modules\Chat\Requests;


use App\Generics\Requests\JsonRequest;

class IndexChatRequest extends JsonRequest
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
                'min:1'
            ],
            'filter' => [
                'sometimes',
                'string',
                'min:1',
            ]
        ];
    }
}
