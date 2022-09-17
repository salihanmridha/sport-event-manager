<?php

namespace App\Http\Requests\API\Auth\Me;

use App\Enums\GenderEnum;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
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
    public function rules(): array
    {
        $userId = auth()->id();

        return [
            'country_id' => [
                'nullable',
                'exists:countries,id'
            ],
            'first_name' => [
                'nullable',
                'string',
                'regex:/^(?:(?!.*[ ]{2})(?!(?:.*[\']){2})(?!(?:.*[-]){})(?:[a-zA-Z_:;\.\-\ \p{L}\'-]{1,50}$))$/',
                'between:1,50',
            ],
            'last_name' => [
                'nullable',
                'string',
                'regex:/^(?:(?!.*[ ]{2})(?!(?:.*[\']){2})(?!(?:.*[-]){})(?:[a-zA-Z_:;\.\-\ \p{L}\'-]{1,50}$))$/',
                'between:1,50',
            ],
            'bio' => [
                'nullable',
                'string',
                'max:255',
            ],
            'email' => [
                'nullable',
                'string',
                'email_custom',
                'max:255',
                'unique:users,email,'. $userId . ',id',
            ],
            'phone' => [
                'nullable',
                'string',
                'max:20',
                'unique:users,phone,'. $userId . ',id,deleted_at,NULL',

            ],
            'gender' => [
                'nullable',
                GenderEnum::toRule()
            ],
            'birth_date' => [
                'nullable',
                'date_format:' . User::BIRTH_DATE_FORMAT,
                'after_or_equal:' . User::MINIMUM_BIRTH_DATE,
            ],
            'avatar' => [
                'nullable',
                'url_rule'
            ],
            'img_background' => [
                'nullable',
                'url_rule'
            ],
        ];
    }
}
