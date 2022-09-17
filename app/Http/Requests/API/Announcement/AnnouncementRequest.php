<?php

namespace App\Http\Requests\API\Announcement;

use App\Rules\ExistingEmailRule;
use Illuminate\Foundation\Http\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

// define('MAX_TEAM_SLOT', 4);
// define('MAX_PLAYER_TEAM_SLOT', 10);

class AnnouncementRequest extends FormRequest
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
        // if (isset($input['event_type'])) {
        //     if ($input['event_type'] == 'pickup') {
        //         $max_teams = ['min:0', 'max:' . MAX_TEAM_SLOT];
        //         $max_player_per_team = ['min:0', 'max:' . MAX_PLAYER_TEAM_SLOT];
        //         // $sport = 'required';
        //         $is_set_role = ['boolean'];
        //         $arrRequired = $this->checkValidatePickUp($input);
        //         $list_position = $arrRequired['list_position'];
        //         $number_of_position = $arrRequired['number_of_position'];
        //         $select_only_once = $arrRequired['select_only_once'];
        //     }
        //     if ($input['event_type'] == 'sport') {
        //         $max_teams =
        //             isset($input['application_type']) &&
        //             $input['application_type'] == 'team' &&
        //             isset($input['is_unlimit_max']) &&
        //             !$input['is_unlimit_max']
        //                 ? ['min:0']
        //                 : [];
        //         $is_unlimit_max = 'required';
        //         $max_player_per_team =
        //             isset($input['application_type']) &&
        //             $input['application_type'] == 'individual' &&
        //             isset($input['is_unlimit_max']) &&
        //             !$input['is_unlimit_max']
        //                 ? ['min:0']
        //                 : [];
        //         $sport = '';
        //     }
        //     if ($input['event_type'] == 'league') {
        //         $max_teams = ['min:0'];
        //         $is_unlimit_max = 'required';
        //         $max_player_per_team = ['min:0'];
        //         // $sport = 'required';
        //         $is_set_role = ['boolean'];
        //     }
        // }

        return [
            'type' => ['required', 'in:sport,pickup,session'],
            'status' => ['required', 'in:publish,unpublish'],
            'sport' => [$sport],
            'max_team' => array_merge(['required', 'numeric'], $max_teams),
            'max_player_per_team' => array_merge(
                ['required', 'numeric'],
                $max_player_per_team
            ),
            'is_unlimit_max' => [$is_unlimit_max],
            'start_date' => ['required'],
            'end_date' => ['required'],
            'title' => ['required'],
            'is_paid' => ['boolean'],
            'is_public' => ['boolean'],
            'private_code' =>
                isset($input['is_public']) && $input['is_public']
                    ? ['required']
                    : '',
            'is_set_role' => $is_set_role,
            'list_position' => $list_position,
            'number_of_position' => $number_of_position,
            'select_only_once' => $select_only_once,
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
