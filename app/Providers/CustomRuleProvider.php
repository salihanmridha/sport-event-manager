<?php

namespace App\Providers;

use App\Rules\EmailRule;
use App\Rules\PasswordRule;
use App\Rules\UrlRule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class CustomRuleProvider extends ServiceProvider
{
    protected $rules = [
        UrlRule::class,
        EmailRule::class,
        PasswordRule::class
    ];

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerValidationRules();
    }

    private function registerValidationRules()
    {
        foreach ($this->rules as $class) {
            $obj = app($class);
            $alias = $obj->__toString();
            if ($alias) {
                Validator::extend($alias, $class . '@passes', $obj->message());
            }
        }
    }
}
