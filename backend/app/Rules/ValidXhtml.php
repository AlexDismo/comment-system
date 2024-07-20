<?php

namespace App\Rules;

use HTMLPurifier;
use HTMLPurifier_Config;
use Illuminate\Contracts\Validation\Rule;

class ValidXhtml implements Rule
{
    public function passes($attribute, $value)
    {
        $config = HTMLPurifier_Config::createDefault();
        $config->set('HTML.Allowed', 'a[href],code,i,strong,p,br');
        $purifier = new HTMLPurifier($config);
        $cleanHtml = $purifier->purify($value);
        return $value === $cleanHtml;
    }

    public function message()
    {
        return 'The :attribute must be valid XHTML and contain only certain tags.';
    }
}
