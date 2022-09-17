<?php

namespace App\Http\Requests\API\Auth;

use App\Enums\GenderEnum;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Spatie\Enum\Laravel\Rules\EnumRule;


class RegisterSocialRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => [
                'required',
                'string',
                'regex:/[\w\[\]`!@#$%\^&*()={}:;<>+"\'-?]*/',
                'between:1,50',
            ],
            'last_name' => [
                'required',
                'string',
                'regex:/[\w\[\]`!@#$%\^&*()={}:;<>+"\'-?]*/',
                'between:1,50',
            ],
            'gender' => [
                'nullable',
                new EnumRule(GenderEnum::class)
            ],
            'birth_date' => [
                'nullable',
                'date_format:' . User::BIRTH_DATE_FORMAT,
                'after_or_equal:' . User::MINIMUM_BIRTH_DATE,
            ],
            'country_id' => [
                'required',
                'exists:countries,id',
            ],
            'phone' => [
                'required',
                'string',
                'max:20',
            ],
            'phone_code' => [
                'required',
                'string',
                // 'between:3,5',
            ],
            'provider_name' => [
                'required'
            ],
            'access_token' => [
                'required',
            ],
            'access_token_secret' => [
                'required_if:provider_name,twitter'
            ],
            'sport_ids' => [
                ['required', 'exists:sports,id'],
            ]
        ];
    }
    
}
