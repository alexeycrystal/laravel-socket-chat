<?php


namespace App\Generics\Requests;


use Illuminate\Foundation\Http\FormRequest;

class JsonRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function validationData()
    {
        if (is_array($this->all()))
            return $this->all();

        return json_decode($this->getContent(),  true, 512, JSON_BIGINT_AS_STRING) ?? [];
    }
}
