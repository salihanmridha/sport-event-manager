<?php

namespace App\Http\Requests\API\Auth;

use App\Rules\ExistingEmailRule;
use Illuminate\Foundation\Http\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    #[ArrayShape(['email' => "string", 'password' => "string", 'device_name' => "string"])]
    public function rules(): array
    {
        return [
            'email' => [
                'required',
                 'exists:users,email'
            ],
            'password' => [
                'required',
            ],
            'device_name' => [
                'required',
            ],
        ];
    }

    #[ArrayShape(['email.exists' => "string"])]
    public function messages(): array
    {
        return [
            'email.exists' => 'Email is not registered.'
        ];
    }
}
