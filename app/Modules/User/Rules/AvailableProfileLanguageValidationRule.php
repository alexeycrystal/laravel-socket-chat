<?php


namespace App\Modules\User\Rules;


use Illuminate\Contracts\Validation\Rule;

class AvailableProfileLanguageValidationRule implements Rule
{
    public function passes($attribute, $value)
    {
        $languages = config('lang.available_languages');

        if(in_array($value, $languages))
            return true;

        return false;
    }

    public function message()
    {
        return 'The selected language is not supported';
    }
}
