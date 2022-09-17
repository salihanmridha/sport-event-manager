<?php

namespace App\Http\Requests\API\Event;

use App\Rules\ExistingEmailRule;
use Illuminate\Foundation\Http\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

// define('MAX_TEAM_SLOT', 4);
// define('MAX_PLAYER_TEAM_SLOT', 10);

class EventJoinRequest extends FormRequest
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
        return [
            'position_id' => ['sometimes'],
            'squad_id' => ['sometimes'],
            'team_id' => ['sometimes'],
        ];
    }
}
