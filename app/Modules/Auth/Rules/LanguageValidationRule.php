<?php


namespace App\Modules\Auth\Rules;


use Illuminate\Contracts\Validation\Rule;

class LanguageValidationRule implements Rule
{

    public function passes($attribute, $value)
    {
        $availableLanguages = config('lang.available_languages');

        if(in_array($value, $availableLanguages))
            return true;

        return false;
    }

    public function message()
    {
        return 'Invalid language!';
    }
}
