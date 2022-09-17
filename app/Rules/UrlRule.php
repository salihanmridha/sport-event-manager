<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class UrlRule implements Rule
{
    protected string $alias = 'url_rule';
    public const REGEX = '/^http(s)?:\/\/([\w\-]+\.)+[\w\-]+(\/[\w\-.\/?%&=]*)?/';


    public function __toString()
    {
        return $this->alias;
    }
    /**
     * Create a new rule instance.
     *
     */
    public function __construct()
    {
    }



    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        return preg_match(self::REGEX, $value) || empty($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return __('validation.url');
    }
}
