<?php

namespace App\Http\Requests\API\Event;

use App\Rules\ExistingEmailRule;
use Illuminate\Foundation\Http\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

class EventUpdateRequest extends FormRequest
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
        $input = request()->all();

        $max_teams = [];
        $max_player_per_team = [];
        $sport = '';
        $is_unlimit_max = '';
        $is_set_role = [];
        $list_position = [];
        $number_of_position = [];
        $select_only_once = [];
        $title = ['required'];
        $selected_team_id = [];

        if (isset($input['event_type'])) {
            if ($input['event_type'] == 'pickup') {
                $max_teams = [
                    'required',
                    'numeric',
                    'min:0',
                    'max:' . MAX_TEAM_SLOT,
                ];
                $max_player_per_team = ['min:0', 'max:' . MAX_PLAYER_TEAM_SLOT];
                // $sport = 'required';
                $is_set_role = ['boolean'];
                $arrRequired = $this->checkValidatePickUp($input);
                if ($input['application_type'] != 'team') {
                    $list_position = $arrRequired['list_position'];
                    $number_of_position = $arrRequired['number_of_position'];
                } else {
                    $selected_team_id = ['required', 'numeric'];
                }
                $select_only_once = $arrRequired['select_only_once'];
                $title = [''];
            } else {
                $max_teams =
                    isset($input['application_type']) &&
                    $input['application_type'] == 'team' &&
                    isset($input['is_unlimit_max']) &&
                    !$input['is_unlimit_max']
                        ? ['required', 'numeric', 'min:0']
                        : [];
                $max_player_per_team =
                    isset($input['application_type']) &&
                    $input['application_type'] == 'individual' &&
                    isset($input['is_unlimit_max']) &&
                    !$input['is_unlimit_max']
                        ? ['required', 'numeric', 'min:0']
                        : [];
            }
            if ($input['event_type'] == 'sport') {
                $is_unlimit_max = 'required';
                $sport = '';
            }
            if ($input['event_type'] == 'session') {
                $is_unlimit_max = 'required';
            }
            if ($input['event_type'] == 'league') {
                $is_unlimit_max = 'required';
            }
        }
        return [
            'event_type' => ['required', 'in:league,sport,pickup,session'],
            'sport' => [$sport],
            'max_team' => $max_teams,
            'max_player_per_team' => $max_player_per_team,
            'is_unlimit_max' => [$is_unlimit_max],
            'application_type' => ['required', 'in:team,individual'],
            'gender' => ['nullable', 'in:female,male,lgbt,all'],
            'start_date_time' => ['required'],
            'end_date_time' => ['required', 'after:start_date_time'],
            'location' => ['required'],
            'lat' => ['required'],
            'long' => ['required'],
            'is_paid' => ['boolean'],
            'is_public' => ['boolean'],
            'private_code' =>
                isset($input['is_public']) && !$input['is_public']
                    ? ['required']
                    : '',
            'is_set_role' => $is_set_role,
            'list_position' => $list_position,
            'number_of_position' => $number_of_position,
            'select_only_once' => $select_only_once,
            'age_group' => ['required'],
            'title' => $title,
            'selected_team_id' => $selected_team_id,
        ];
    }

    #[ArrayShape(['number_of_position.required' => "string",'select_only_once.required' => "string"])]
    public function messages(): array
    {
        return [
            'number_of_position.required' =>
                'Number of positions is not correct',
            'select_only_once.required' =>
                'Creator cannot apply more than one position',
        ];
    }

    public function checkValidatePickUp($input = [])
    {
        $list_position = [];
        $number_of_position = [];
        $select_only_once = [];
        if (!$input['is_set_role']) {
            $list_position = 'required';
            if (isset($input['list_position'])) {
                $number_of_position =
                    count($input['list_position']) !=
                    $input['max_player_per_team']
                        ? 'required'
                        : '';
                if (!$number_of_position) {
                    $count = 0;
                    foreach ($input['list_position'] as $item) {
                        if ($item['status']) {
                            $count++;
                        }
                    }
                    if ($count >= 2) {
                        $select_only_once = 'required';
                    }
                }
            }
        }
        return [
            'list_position' => $list_position,
            'number_of_position' => $number_of_position,
            'select_only_once' => $select_only_once,
        ];
    }
}
