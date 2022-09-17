<?php

namespace App\Http\Requests\API\Auth;

use App\Rules\ExistingEmailRule;
use Illuminate\Foundation\Http\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

class SocialLoginRequest extends FormRequest
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
    #[ArrayShape(['provider_name' => "string", 'access_token' => "string"])]
    public function rules(): array
    {
        return [
            'provider_name' => [
                'required'
            ],
            'access_token' => [
                'required',
            ],
            'access_token_secret' => [
                'required_if:provider_name,twitter'
            ]
        ];
    }
}
