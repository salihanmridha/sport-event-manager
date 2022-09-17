<?php

namespace App\Http\Requests\API\Auth;

use App\Enums\GenderEnum;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rule;
use Spatie\Enum\Laravel\Rules\EnumRule;


class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users'
            ],
            'password' => [
                'required',
                'string',
                // 'regex:/^(?=.*[0-9])(?=.*[a-zA-Z])(?=\S+$).{6,20}$/',
            ],
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
            'sport_ids' => [
                ['required', 'exists:sports,id'],
            ]
        ];
    }
    
}
