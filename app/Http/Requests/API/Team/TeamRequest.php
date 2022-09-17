<?php

namespace App\Http\Requests\API\Team;

use App\Rules\ExistingEmailRule;
use Illuminate\Foundation\Http\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

class TeamRequest extends FormRequest
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
     * Get the validation rules that apply to the request.sometimes
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required'],
            'sport_id' => ['required', 'numeric'],
            'gender' => ['sometimes', 'in:female,male,lgbt,all'],
            'age_group' => ['sometimes', 'numeric', 'in:0,1,2,3,4,5,6'],
            'invite_user' => ['required_if:set_coach,==,1'],
            'invite_email' => ['required_if:set_coach,==,2'],
            'level_id' => ['sometimes', 'numeric'],
            'org_role_id' => ['sometimes', 'numeric'],
        ];
    }
}
