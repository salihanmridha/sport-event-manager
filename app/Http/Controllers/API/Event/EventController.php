<?php

namespace App\Http\Controllers\API\Event;

use App\Enums\AnnouncementStatusEnum;
use App\Enums\AnnouncementTypeEnum;
use App\Enums\EventStatusEnum;
use App\Enums\EventTypeEnum;
use App\Enums\GenderEnum;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Requests\API\Event\EventJoinRequest;
use App\Models\Event;
use App\Models\Invite;
use App\Models\EventSquad;
use App\Models\EventPosition;
use App\Models\UserEvent;
use App\Models\User;
use App\Models\TeamMember;
use App\Models\Team;
use Validator;
use App\Http\Resources\EventResource;
use App\Http\Resources\EventDetailResource;
use DateTime;
use DatePeriod;
use DateInterval;
use App\Http\Requests\API\Event\EventRequest;
use Carbon\Carbon;

use App\Http\Requests\API\Event\EventUpdateRequest;
use App\Http\Resources\AnnouncementResource;
use App\Models\Announcement;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\API\Help\HelpController;


define('MAX_TEAM_SLOT', 4);
define('MAX_PLAYER_TEAM_SLOT', 10);
const LIST_APPLICATION_TYPE = ['team', 'individual'];
const LIST_TYPE_EVENT = ['pickup', 'sport', 'league', 'session'];
const LIST_AGE = [
    0 => ['start_age' => 0, 'end_age' => 99],
    1 => ['start_age' => 0, 'end_age' => 2],
    2 => ['start_age' => 3, 'end_age' => 12],
    3 => ['start_age' => 13, 'end_age' => 19],
    4 => ['start_age' => 20, 'end_age' => 30],
    5 => ['start_age' => 31, 'end_age' => 45],
    6 => ['start_age' => 46, 'end_age' => 99],
];

class EventController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request
        ->user();
        $sportIds = $request
            ->user()
            ->sports()
            ->pluck('sports.id')
            ->toArray();

        $input = $request->all();
        $sportIdsFilterString = isset($input['sport_ids'])
            ? $input['sport_ids']
            : null;
        $sportIdsFilter = explode(',', $sportIdsFilterString);
        $limit = isset($input['perpage']) ? $input['perpage'] : 20;

        $sportIds = $sportIdsFilterString != null && !empty($sportIdsFilter)? $sportIdsFilter: $sportIds;

        $age = $request
        ->user()->age;
        $events = Event::selectRaw('id, created_at, "event" as type')
            ->whereNull('deleted_at')
            ->whereIn('sport_id', $sportIds)
            ->orWhereNull('sport_id')
            ->where('end_date_time', '>=', Carbon::now())
            ->where('status', EventStatusEnum::on_going())
            // ->where('event_type', EventTypeEnum::pickup())
            ->where('start_age', '<=', $age)
            ->where('end_age', '>=', $age)
            ->where('gender', $user->gender)
            ->orWhere('gender', GenderEnum::all());

        $union = Announcement::selectRaw('id, created_at, "announcement" as type')
            ->where('status', AnnouncementStatusEnum::publish())
            ->where('type', AnnouncementTypeEnum::news())
            ->where('end_date', '>=', Carbon::now())
            ->where('start_date', '<=', Carbon::now())
            ->union($events)
            ->orderByDesc('created_at')
            ->paginate($limit);
        $sortedIds = $union->map(function ($item) {
            return $item->type.$item->id;
        })->toArray();
        $eventIds = $union->groupBy('type')->get('event')?->pluck('id')->toArray() ?? [];
        $announcementIds = $union->groupBy('type')->get('announcement')?->pluck('id')->toArray() ?? [];

        $events = Event::whereNull('deleted_at')
            ->whereIn('id', $eventIds)
            ->with([
                'user_create:email,id,first_name,last_name',
                'user_joined:email,id,first_name,last_name',
                'team_joined:id,name,oganization_name,oganization_url,division,season,gender,start_age,end_age',
            ])
            ->addSelect(DB::raw('*, "event" as dataType'))
            ->get();
        $announcements = Announcement::whereNull('deleted_at')
            ->whereIn('id', $announcementIds)
            ->addSelect(DB::raw('*, "announcement" as dataType'))
            ->get();

        $eventCollection = EventResource::collection($events, ['a' => 'fd']);
        $announcementCollection = AnnouncementResource::collection($announcements);
        $union = $eventCollection->merge($announcementCollection->collection)->sortBy(function ($item) use($sortedIds) {
            $k = $item->dataType.$item->id;
            $key = array_search($k, $sortedIds);
            return $key;
        })->values()->all();
        return $this->sendResponse(
            $union,
            'success'
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function schedule(Request $request)
    {
        $user = $request->user();

        $limit = isset($input['perpage']) ? $input['perpage'] : 20;

        $results = $user
            ->joinedAndTeamEvents()
            ->whereNull('events.deleted_at')
            ->where('events.status', EventStatusEnum::on_going())
            ->where('end_date_time', '>=', Carbon::now())
            ->simplePaginate($limit);
        return $this->sendResponse(
            EventResource::collection($results),
            'success'
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function scheduleCalendar(Request $request)
    {
        $user = $request->user();
        $input = $request->all();

        $from = isset($input['from']) ? $input['from'] : null;
        $to = isset($input['to']) ? $input['to'] : null;

        if (!$this->validateDate($from) || !$this->validateDate($to)) {
            return $this->sendError(
                'Invalid input date format! required YYYY-MM-DD',
                []
            );
        }
        // dd(date('Y-m-d 00:00:00', strtotime($from)));

        // $results = $user
        //     ->joinedEvents()
        //     ->whereNull('events.deleted_at')
        //     ->where('events.status', EventStatusEnum::on_going())
        //     // ->where(
        //     //     'end_date_time',
        //     //     '>=',
        //     //     date('Y-m-d H:i:s', strtotime($from))
        //     // )
        //     // ->where(
        //     //     'start_date_time',
        //     //     '<=',
        //     //     date('Y-m-d H:i:s', strtotime($to))
        //     // )
        //     ->orderBy('start_date_time', 'ASC')
        //     ->get()
        //     ->toArray();

        $results = [];
        try {
            $results = $user->joinedAndTeamEvents()
            ->where('status', EventStatusEnum::on_going())
            ->where(
                'end_date_time',
                '>=',
                date('Y-m-d 00:00:00', strtotime($from))
            )
            ->where(
                'start_date_time',
                '<=',
                date('Y-m-d 23:59:59', strtotime($to))
            )
            ->with(['sport:id,name'])->get()->toArray();
        } catch (\Throwable $th) {
            return $this->sendError('An error occurred');
        }
       
        $arr_calendar = [];
        foreach ($results as $k => $v) {
            if (
                isset($v['start_date_time']) &&
                !empty($v['start_date_time']) &&
                isset($v['end_date_time']) &&
                !empty($v['end_date_time'])
            ) {
                // Nếu có cả start_date && end_date
                $v_start_date = date('Y-m-d', strtotime($v['start_date_time']));
                $v_end_date = date('Y-m-d', strtotime($v['end_date_time']));
                if ($v_start_date <= $from) {
                    $v_start_date = $from;
                }
                if ($v_end_date >= $to) {
                    $v_end_date = $to;
                }
                $interval = DateInterval::createFromDateString('1 day');
                $dbegin = new DateTime($v_start_date);
                $dend = new DateTime($v_end_date);
                $period = new DatePeriod($dbegin, $interval, $dend);

                $arr_calendar[$v_start_date] = $v_start_date;
                foreach ($period as $dt) {
                    $datestring = $dt->format('Y-m-d');
                    $arr_calendar[$datestring] = $datestring;
                }
                $arr_calendar[$v_end_date] = $v_end_date;
            } elseif (
                isset($v['start_date_time']) &&
                !empty($v['start_date_time'])
            ) {
                // Nếu chỉ có start_date => add vào calendar
                $datestring = strtotime(
                    'Y-m-d',
                    strtotime($v['start_date_time'])
                );
                if ($datestring >= $from && $datestring <= $to) {
                    $arr_calendar[$datestring] = $datestring;
                }
            } elseif (
                isset($v['end_date_time']) &&
                !empty($v['end_date_time'])
            ) {
                // Nếu chỉ có start_date => add vào calendar
                $datestring = strtotime(
                    'Y-m-d',
                    strtotime($v['end_date_time'])
                );
                if ($datestring >= $from && $datestring <= $to) {
                    $arr_calendar[$datestring] = $datestring;
                }
            }
        }

        return $this->sendResponse($arr_calendar, 'success');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function scheduleByDate(Request $request)
    {
        $user = $request->user();
        $input = $request->all();
        $date_search = isset($input['date'])
            ? strtotime(str_replace('/', '-', $input['date']))
            : time();
        $date_search = date('Y-m-d', $date_search);
        $daysInWeek = $this->week_from_monday($date_search);
        $results = [];
        $list_event = [];
        
        try {
            // user joined event
            // $list_event = $user
            // ->joinedEvents()
            // ->whereNull('events.deleted_at')
            // ->where('events.status', EventStatusEnum::on_going())
            // ->with(['sport:id,name'])
            // ->get()
            // ->toArray();
            // user's group joined event
            $list_event = $user->joinedAndTeamEvents()
            ->where('status', EventStatusEnum::on_going())
                ->with(['sport:id,name'])->get()->toArray();

        } catch (\Throwable $th) {
        }
       

        if (count($list_event) > 0) {
            foreach ($daysInWeek as $date) {
                $list_event_this_day = [];
                foreach ($list_event as $item) {
                    $start_date_time = date(
                        'Y-m-d',
                        strtotime($item['start_date_time'])
                    );
                    $end_date_time = date(
                        'Y-m-d',
                        strtotime($item['end_date_time'])
                    );
                    if (
                        strtotime($start_date_time) <= strtotime($date) &&
                        strtotime($end_date_time) >= strtotime($date)
                    ) {
                        $list_event_this_day[] = [
                            'id' => $item['id'],
                            'event_type' => $item['event_type'],
                            'title' => $item['title'],
                            'start_date_time' => $item['start_date_time'],
                            'end_date_time' => $item['end_date_time'],
                            'location' => $item['location'],
                            'application_type' => $item['application_type'],
                            'sport' => [
                                'sport_id' => isset($item['sport']['id'])
                                    ? $item['sport']['id']
                                    : null,
                                'sport_name' => isset($item['sport']['name'])
                                    ? $item['sport']['name']
                                    : null,
                                'image' => isset(
                                    $item['sport']['icon'][0]['url']
                                )
                                    ? $item['sport']['icon'][0]['url']
                                    : null,
                            ],
                        ];
                    }
                }
                if (count($list_event_this_day) > 0) {
                    $results[] = [
                        'date' => $date,
                        'events' => $list_event_this_day,
                    ];
                }
            }
        }

        return $this->sendResponse($results, 'success');
    }

    function week_from_monday($date_search)
    {
        $start_date = $this->isSunday($date_search)
            ? date('Y-m-d', strtotime($date_search))
            : date(
                'Y-m-d',
                strtotime('sunday last week', strtotime($date_search))
            );
        $dates[0] = $start_date;
        for ($i = 1; $i < 7; $i++) {
            $dates[$i] = date(
                'Y-m-d',
                strtotime($start_date . ' +' . $i . ' day')
            );
        }
        return $dates;
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EventRequest $eventRequest)
    {
        $user = $eventRequest->user();
        $input = $eventRequest->all();
        $user_id = auth()->user()->id;

        if (
            empty($input['event_type']) ||
            !in_array($input['event_type'], LIST_TYPE_EVENT)
        ) {
            return $this->sendError('type is not available or incorrect');
        }

        $list_position = isset($input['list_position'])
            ? json_decode(json_encode($input['list_position']), true)
            : [];
        // VALIDATE GENDER,SPORT,ROLE
        if($input['event_type'] == LIST_TYPE_EVENT[0] && $input['application_type'] == LIST_APPLICATION_TYPE[1]){ // application_type = individual
            
            if($input['gender'] != 'all' && $user->gender->value != $input['gender']){
                return $this->sendError('Gender is not appropriate for the event!');
            }
            $_age = floor((time() - strtotime($user->birth_date)) / 31556926);
            if(LIST_AGE[$input['age_group']]['start_age'] > $_age || $_age > LIST_AGE[$input['age_group']]['end_age']){
                return $this->sendError('Age is not appropriate for the event!');
            }
            $listSportId = $user->sports()->get()->pluck('id')->toArray(); 
            if(!in_array($input['sport_id'], $listSportId)){
                return $this->sendError('Sport is not appropriate for the event!');
            }

        }
        if($input['event_type'] == LIST_TYPE_EVENT[0] && $input['application_type'] == LIST_APPLICATION_TYPE[0]){ // application_type = team
            $existTeam = Team::find($input['selected_team_id']);
            if(empty($existTeam)){
                return $this->sendError('Team not found!');
            }            

            // if(is_null($existTeam->gender) || $input['gender'] != 'all' && $existTeam->gender->value != $input['gender']){
            //     return $this->sendError('Gender is not appropriate for the event!');
            // }

            if($existTeam->age_group != 0 &&  $input['age_group'] != 0 && $input['age_group'] !=  $existTeam->age_group){
                return $this->sendError('Gender is not appropriate for the event!');
            }
            $help = new HelpController();
            $dataCheckPermission = $help->getPermissionRole($existTeam->id);
            if (!in_array('team_event_management', $dataCheckPermission)) {
                return $this->sendError('User does not have permission.');
            }

            if($existTeam->sport_id != $input['sport_id']){
                return $this->sendError('Sport is not appropriate for the event!');
            }
        }

        // ADD EVENT
        $datetime = new DateTime(date('Y-m-d H:i:s'));

        $format_create_time_now = $datetime->format(DateTime::ATOM);
        $dataInsert = $this->customData($input);
        $dataInsert['created_at'] = $format_create_time_now;
        $dataInsert['updated_at'] = $format_create_time_now;
        $dataInsert['creator_id'] = auth()->user()->id;
        $event = null;
        $eventObj = null;

        try {
            $eventObj = Event::create($dataInsert);
            $event = $eventObj->toArray();
        } catch (\Throwable $th) {
            return $this->sendError('An error occurred 1');
        }
        // ADD INVITES
        if (!empty($event)) {
            // ADD EVENT_SQUAD NEU event_type = pickup & application_type = individual
            $event_type = $event['event_type'];
            $event_id_created = $event['id'];
            $source_type =
                $event['application_type'] == 'team' ? 'team' : 'event';
            $target_type =
                $event['application_type'] == 'team' ? 'team' : 'user';
            // UPDATE MEDIA
            $list_media = isset($input['media']) ? $input['media'] : [];
            if (count($list_media) > 0) {
                Media::whereIn('id', $list_media)->update([
                    'model_id' => $event_id_created,
                ]);
            }
            // END UPDATE MEDIA

            if (
                $event_type == LIST_TYPE_EVENT[0] &&
                $event['application_type'] == LIST_APPLICATION_TYPE[1]
            ) {
                // ADD INVITE PLAYER
                $listInvitePlayer = isset($input['invite_players']) ? $input['invite_players'] : [];
                if(count($listInvitePlayer) > 0){
                    try {
                        foreach ($listInvitePlayer as $idPlayer) {
                            $existInvite = Invite::where('source_id', $event_id_created)
                                ->where('source_type', 'event')
                                ->where('target_id', $idPlayer)
                                ->where('target_type', 'user')
                                ->first();
                            if(empty($existInvite)){
                                Invite::create([
                                    'source_id' => $event_id_created,
                                    'source_type' => 'event',
                                    'target_id' => $idPlayer,
                                    'target_type' => 'user',
                                    'first_name' => null,
                                    'last_name' => null,
                                    'email' => null,
                                    'response' => null,
                                ]);
                            }
                            
                        }
                    } catch (\Throwable $th) {
                        return $this->sendError('An error occurred');
                    }
                    
                }
                // END INVITE

                $event_squad = null;
                $first_event_squad = null;
                $max_team = $event['max_team'];
                try {
                    for ($i = 0; $i < $max_team; $i++) {
                        $dataEventSquad = [
                            'event_id' => $event_id_created,
                            'name' => 'Team ' . $i + 1,
                        ];
                        $event_squad = EventSquad::create(
                            $dataEventSquad
                        )->toArray();
                        if ($i == 0) {
                            $first_event_squad = $event_squad;
                        }
                    }
                } catch (\Throwable $th) {
                    return $this->sendError('An error occurred');
                }

                // ADD EVENT_POSITION
                $event_id_created = $event['id'];
                $max_player_per_team = $event['max_player_per_team'];
                $is_set_role = $event['is_set_role'];

                $position_id_user_created = null;
                if (!$is_set_role) {
                    for ($i = 0; $i < $max_player_per_team; $i++) {
                        $name_position = isset($list_position[$i]['name'])
                            ? $list_position[$i]['name']
                            : null;
                        $weight_position = isset($list_position[$i]['weight'])
                            ? $list_position[$i]['weight']
                            : null;
                        $status_player_selected = isset(
                            $list_position[$i]['status']
                        )
                            ? $list_position[$i]['status']
                            : null;
                        $dataEventPosition = [
                            'event_id' => $event_id_created,
                            'name' => $name_position,
                            'weight' => $weight_position,
                        ];
                        $eventPosition = null;
                        try {
                            $eventPosition = EventPosition::create(
                                $dataEventPosition
                            )->toArray();
                        } catch (\Throwable $th) {
                            return $this->sendError('An error occurred');
                        }

                        if ($status_player_selected) {
                            $position_id_user_created = $eventPosition['id'];
                        }
                    }
                }

                // ADD USER_EVENT
                try {
                    $dataUserEvent = [
                        'event_id' => $event_id_created,
                        'user_id' => $user_id,
                        'position_id' => $position_id_user_created,
                        'event_squad_id' => $first_event_squad['id'],
                    ];
                    UserEvent::create($dataUserEvent);
                } catch (\Throwable $th) {
                    return $this->sendError('An error occurred');
                }
            }
            // ADD TEAMS NEU event_type = pickup & application_type = team
            if (
                $event_type == LIST_TYPE_EVENT[0] &&
                $event['application_type'] == LIST_APPLICATION_TYPE[0] &&
                isset($input['selected_team_id'])
            ) {
                $selected_team_id = $input['selected_team_id'];
                $eventObj->team_joined()->sync([
                    $selected_team_id => [
                        'created_at' => $format_create_time_now,
                        'updated_at' => $format_create_time_now,
                    ],
                ]);
            }
        }

        return $this->sendResponse(
            new EventResource($eventObj),
            'Event created successfully.'
        );
    }

    // CUSTORM DATA
    public function customData($input)
    {
        if (empty($input)) {
            return [];
        }
        $dataInsert = [
            'sport_id' => isset($input['sport_id']) ? $input['sport_id'] : null,
            'max_team' => isset($input['max_team']) ? $input['max_team'] : null,
            'is_paid' => isset($input['is_paid']) ? $input['is_paid'] : null,
            'max_player_per_team' => isset($input['max_player_per_team'])
                ? $input['max_player_per_team']
                : null,
            'application_type' => isset($input['application_type'])
                ? $input['application_type']
                : null,
            'gender' => isset($input['gender']) ? $input['gender'] : null,
            'start_date_time' => isset($input['start_date_time'])
                ? $input['start_date_time']
                : null,
            'end_date_time' => isset($input['end_date_time'])
                ? $input['end_date_time']
                : null,
            'location' => isset($input['location']) ? $input['location'] : null,
            'lat' => isset($input['lat']) ? $input['lat'] : null,
            'long' => isset($input['long']) ? $input['long'] : null,
            'is_paid' => isset($input['is_paid']) ? $input['is_paid'] : null,
            'title' => isset($input['title']) ? $input['title'] : null,
            'caption' => isset($input['caption']) ? $input['caption'] : null,
            'is_set_role' => isset($input['is_set_role'])
                ? $input['is_set_role']
                : false,
            'start_age' => isset($input['age_group'])
                ? LIST_AGE[$input['age_group']]['start_age']
                : null,
            'end_age' => isset($input['age_group'])
                ? LIST_AGE[$input['age_group']]['end_age']
                : null,
            'fee' => isset($input['fee']) ? $input['fee'] : null,
            'is_public' => isset($input['is_public'])
                ? $input['is_public']
                : null,
            'status' => 'on_going',
            'event_type' => isset($input['event_type'])
                ? $input['event_type']
                : null,
            'description' => isset($input['description'])
                ? $input['description']
                : null,
            'is_unlimit_max' => isset($input['is_unlimit_max'])
                ? $input['is_unlimit_max']
                : false,
            'about' => isset($input['about']) ? $input['about'] : null,
            'mechanics' => isset($input['mechanics'])
                ? $input['mechanics']
                : null,
            'age_group' => isset($input['age_group'])
                ? $input['age_group']
                : null,
            'private_code' => isset($input['private_code'])
                ? $input['private_code']
                : null,
        ];
        return $dataInsert;
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getDetailEvent($id)
    {
        $results = null;
        try {
            $results = Event::whereNull('deleted_at')
                ->where('id', $id)
                ->with([
                    'sport:id,name,description,max_player_per_team,min_player_per_team,is_require_choose_role',
                    'user_create:email,id,first_name,last_name',
                    'user_joined:email,id,first_name,last_name',
                    'team_joined:id,name,oganization_name,oganization_url,division,season,gender,start_age,end_age',
                    'event_squad:event_id,id,name',
                    'event_position:event_id,id,name,weight',
                ])
                ->first();
        } catch (\Throwable $th) {
        }

        if (is_null($results)) {
            return $this->sendError('Event not found.');
        }
        // EventResource::collection($results);
        // $results = json_decode(json_encode($results[0]), false);
        return $this->sendResponse(
            new EventDetailResource($results),
            'Success'
        );
    }

    // UPDATE EVENT
    public function updateEvent(EventUpdateRequest $eventUpdateRequest, $id)
    {
        $input = $eventUpdateRequest->all();
        $user_id = auth()->user()->id;
        $user = $eventUpdateRequest->user();
        $event = null;
        try {
            $event = Event::find($id);
            if (empty($event)) {
                return $this->sendError('Event not found.');
            }

            if (
                !empty($event) &&
                $event->event_type == LIST_TYPE_EVENT[0] &&
                $event->application_type == LIST_APPLICATION_TYPE[1]
            ) {
                $list_user_joined = $event
                    ->usersJoined()
                    ->get()
                    ->pluck('id')
                    ->toArray();
                if (
                    count($list_user_joined) > 1 ||
                    (count($list_user_joined) > 0 &&
                        $list_user_joined[0] != $user_id)
                ) {
                    return $this->sendError(
                        'Event cannot be changed, someone has joined.'
                    );
                }
                
                // VALIDATE
                if($eventUpdateRequest->has('gender') && $input['gender'] != 'all' && $user->gender->value != $input['gender']){
                    return $this->sendError('Gender is not appropriate for the event!');
                }

                $_age = floor((time() - strtotime($user->birth_date)) / 31556926);
                if($eventUpdateRequest->has('age_group') && LIST_AGE[$input['age_group']]['start_age'] > $_age || $_age > LIST_AGE[$input['age_group']]['end_age']){
                    return $this->sendError('Age is not appropriate for the event!');
                }

                $_listSportId = $user->sports()->get()->pluck('id')->toArray(); 
                if($eventUpdateRequest->has('sport_id') && !in_array($input['sport_id'], $_listSportId)){
                    return $this->sendError('Sport is not appropriate for the event!');
                }
            }

            if (
                !empty($event) &&
                $event->event_type == LIST_TYPE_EVENT[0] &&
                $event->application_type == LIST_APPLICATION_TYPE[0]
            ) {
                $team_joined = $event
                    ->team_joined()
                    ->get()
                    ->toArray();
                if (count($team_joined) > 1) {
                    return $this->sendError(
                        'Event cannot be changed, there is a group that has joined'
                    );
                }

                $existTeam = Team::find($input['selected_team_id']);
                if(empty($existTeam)){
                    return $this->sendError('Team not found!');
                }            

                // if(is_null($existTeam->gender) ||  $eventUpdateRequest->has('gender') && $input['gender'] != 'all' && $existTeam->gender->value != $input['gender']){
                //     return $this->sendError('Gender is not appropriate for the event!');
                // }

                if($eventUpdateRequest->has('age_group') && $existTeam->age_group != 0 &&  $input['age_group'] != 0 && $input['age_group'] !=  $existTeam->age_group){
                    return $this->sendError('Age is not appropriate for the event!');
                }

                $help = new HelpController();
                $dataCheckPermission = $help->getPermissionRole($existTeam->id);
                if (!in_array('team_event_management', $dataCheckPermission)) {
                    return $this->sendError('User does not have permission.');
                }

                if($eventUpdateRequest->has('sport_id') &&  $existTeam->sport_id != $input['sport_id']){
                    return $this->sendError('Sport is not appropriate for the event!');
                }
            }

        } catch (\Throwable $th) {
            return $this->sendError('Event not found.' . $th);
        }

        $defaultTeam = new Event();
        $dataUpdate = $defaultTeam->fill($input)->toArray();
        unset($dataUpdate['upload_photo']);
        unset($dataUpdate['event_ownership']);
        $listNotUpdate = [
            'event_type',
            'start_date_time',
            'end_date_time',
            'location',
            'lat',
            'long',
        ];
        $dataValidate = $event->toArray();
        foreach ($listNotUpdate as $item) {
            if ($dataUpdate[$item] != $dataValidate[$item]) {
                return $this->sendError(
                    'Field ' . $item . ' cannot be changed!'
                );
            }
        }

        $format_create_time_now = date('Y-m-d H:i:s');
        $dataUpdate['updated_at'] = $format_create_time_now;

        // UPDATE MEDIA
        $list_media = isset($input['media']) ? $input['media'] : [];
        $list_img_old = $event->list_images_id()->toArray();

        // REMOVE IMAGE DON'T HAVE
        if (count($list_img_old) > 0) {
            $list_remove_img = array_diff($list_img_old, $list_media);
            if (count($list_remove_img) > 0) {
                Media::destroy(collect(array_values($list_remove_img)));
            }
        }

        if (count($list_media) > 0) {
            Media::whereIn('id', $list_media)->update([
                'model_id' => $event->id,
            ]);
        }
        unset($input['media']);
        // END UPDATE MEDIA
        // ADD INVITES
        if (!empty($event)) {
            $event_id_created = $event->id;
            $event_type = $event->event_type;
            $event_application_type = $event->application_type;

            // ADD EVENT_SQUAD NEU event_type = pickup & application_type = individual
            if (
                $event_type == LIST_TYPE_EVENT[0] &&
                $event_application_type == LIST_APPLICATION_TYPE[1]
            ) {
                // ADD INVITE PLAYER
                $listInvitePlayer = isset($input['invite_players']) ? $input['invite_players'] : [];
                if(count($listInvitePlayer) > 0){
                    try {
                        foreach ($listInvitePlayer as $idPlayer) {
                            $existInvite = Invite::where('source_id', $event_id_created)
                                ->where('source_type', 'event')
                                ->where('target_id', $idPlayer)
                                ->where('target_type', 'user')
                                ->first();
                            if(empty($existInvite)){
                                Invite::create([
                                    'source_id' => $event_id_created,
                                    'source_type' => 'event',
                                    'target_id' => $idPlayer,
                                    'target_type' => 'user',
                                    'first_name' => null,
                                    'last_name' => null,
                                    'email' => null,
                                    'response' => null,
                                ]);
                            }
                            
                        }
                    } catch (\Throwable $th) {
                        return $this->sendError('An error occurred');
                    }
                    
                }
                // END INVITE

                $max_team = $dataUpdate['max_team'];
                $event_squad = null;
                $first_event_squad = null;
                if ($dataUpdate['max_team'] != $event->max_team) {
                    // DELETE EVENT SQUAD
                    EventSquad::where('event_id', $event_id_created)->delete();
                    // END DELETE
                    try {
                        for ($i = 0; $i < $max_team; $i++) {
                            $dataEventSquad = [
                                'event_id' => $event_id_created,
                                'name' => 'Team ' . $i + 1,
                            ];
                            $event_squad = EventSquad::create(
                                $dataEventSquad
                            )->toArray();
                            if ($i == 0) {
                                $first_event_squad = $event_squad;
                            }
                        }
                    } catch (\Throwable $th) {
                        return $this->sendError('An error occurred 0');
                    }
                }

                // ADD EVENT_POSITION
                $max_player_per_team = $input['max_player_per_team'];
                $is_set_role = $dataUpdate['is_set_role'];

                $position_id_user_created = null;
                if (
                    !$is_set_role &&
                    $max_player_per_team != $event->max_player_per_team
                ) {
                    // DELETE EventPosition OLD
                    EventPosition::where(
                        'event_id',
                        $event_id_created
                    )->delete();
                    // END
                    $list_position = json_decode(
                        json_encode($input['list_position']),
                        true
                    );
                    for ($i = 0; $i < $max_player_per_team; $i++) {
                        $name_position = isset($list_position[$i]['name'])
                            ? $list_position[$i]['name']
                            : null;
                        $weight_position = isset($list_position[$i]['weight'])
                            ? $list_position[$i]['weight']
                            : null;
                        $status_player_selected = isset(
                            $list_position[$i]['status']
                        )
                            ? $list_position[$i]['status']
                            : null;
                        $dataEventPosition = [
                            'event_id' => $event_id_created,
                            'name' => $name_position,
                            'weight' => $weight_position,
                        ];
                        $eventPosition = null;
                        try {
                            $eventPosition = EventPosition::create(
                                $dataEventPosition
                            )->toArray();
                        } catch (\Throwable $th) {
                            return $this->sendError('An error occurred 1');
                        }

                        if ($status_player_selected) {
                            $position_id_user_created = $eventPosition['id'];
                        }
                    }
                }

                // ADD USER_EVENT
                $data_user_event = [
                    'position_id' => $position_id_user_created,
                ];
                if (!empty($first_event_squad)) {
                    $data_user_event['event_squad_id'] =
                        $first_event_squad['id'];
                } else {
                    try {
                        $event_squad = EventSquad::where(
                            'event_id',
                            $event_id_created
                        )->first();
                        $data_user_event['event_squad_id'] =
                            $event_squad->id;
                    } catch (\Throwable $th) {
                    }
                }
                try {
                    $user_event = UserEvent::Where(
                        'event_id',
                        $event_id_created
                    )
                        ->where('user_id', $user_id)
                        ->first();
                    if (!empty($user_event)) {
                        $user_event
                            ->Where('event_id', $event_id_created)
                            ->where('user_id', $user_id)
                            ->update($data_user_event);
                    } else {
                        $data_user_event['event_id'] = $event_id_created;
                        $data_user_event['user_id'] = $user_id;
                        UserEvent::create($data_user_event);
                    }
                } catch (\Throwable $th) {
                    return $this->sendError('An error occurred 2');
                }
                // REMOVE TEAM EXIST
                $event->team_joined()->sync([]);
            }

            // ADD TEAMS NEU event_type = pickup & application_type = team
            if (
                $event_type == LIST_TYPE_EVENT[0] &&
                $event['application_type'] == LIST_APPLICATION_TYPE[0] &&
                isset($input['selected_team_id'])
            ) {
                $selected_team_id = $input['selected_team_id'];
                $eventObj->team_joined()->sync([
                    $selected_team_id => [
                        'created_at' => $format_create_time_now,
                        'updated_at' => $format_create_time_now,
                    ],
                ]);

                // REMOVE USER EXIST
                $event->user_joined()->sync([]);
            }
        }
        // END

        $res = $event->update($dataUpdate);
        if ($res) {
            return response()->json(['success' => true], 200);
        } else {
            return response()->json(['success' => false], 404);
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $event = Event::find($id);
        $user_id = auth()->user()->id;
        if (empty($event)) {
            return $this->sendError('Event Not Found!');
        }
        if ($event->creator_id != $user_id) {
            return $this->sendResponse([], 'User does not have permission.');
        }

        $datetime = new DateTime(date('Y-m-d H:i:s'));
        $format_create_time_now = $datetime->format(DateTime::ATOM);

        $res = $event->update(['deleted_at' => $format_create_time_now]);
        if ($res) {
            return response()->json(['success' => true], 200);
        } else {
            return response()->json(['success' => false], 404);
        }
    }

    public function eventBookingInfo($id)
    {
        $results = null;
        try {
            $results = Event::whereNull('events.deleted_at')
                ->where('id', $id)
                ->with([
                    'event_squad:event_id,id,name',
                    'user_joined:email,id,first_name,last_name',
                    'event_position:event_id,id,name,weight',
                ])
                ->get();
        } catch (\Throwable $th) {
        }

        if (is_null($results)) {
            return $this->sendError('Event not found.');
        }
        // EventResource::collection($results);
        // $results = json_decode(json_encode($results[0]), false);
        return $this->sendResponse(
            EventResource::collection($results),
            'Success'
        );
    }

    public function join(EventJoinRequest $request, $event_id)
    {
        try {
            $event = Event::withCount("user_joined")->withCount("team_joined")->find($event_id);
            $user = $request->user();

            if(empty($event)){
                return $this->sendError('Event not found!');
            }

            
            switch ($event->application_type) {
                case 'team':
                    $teamId = $request->input('team_id');
                    $existTeam = Team::find($teamId);
                    if(empty($existTeam)){
                        return $this->sendError('Team not found!');
                    }

                    $help = new HelpController();
                    $dataCheckPermission = $help->getPermissionRole($teamId);
                    if (!in_array('team_event_management', $dataCheckPermission)) {
                        return $this->sendError('User does not have permission.');
                    }

                    // if(is_null($existTeam->gender) || $event->gender != 'all' && $existTeam->gender->value != $event->gender){
                    //     return $this->sendError('Gender is not appropriate for the event!');
                    // }

                    if($existTeam->age_group != 0 &&  $event->age_group != 0 && $event->age_group !=  $existTeam->age_group){
                        return $this->sendError('Gender is not appropriate for the event!');
                    }

                    if(in_array($event->event_type, ['pickup', 'league'])){
                        if($existTeam->sport_id != $event->sport_id){
                            return $this->sendError('Sport is not appropriate for the event!');
                        }
                    }
                     // check limit event
                    if( $event->event_type == 'pickup' && $event->team_joined_count >= $event->max_team || 
                        $event->event_type == 'league' && $event->team_joined_count >= $event->max_team ){
                        return $this->sendError('The number of users participating has been maxed');
                    }
                    if(($event->event_type == 'session' && !$event->is_unlimit_max && $event->team_joined_count >= $event->max_team) ||
                        ($event->event_type == 'sport' && !$event->is_unlimit_max && $event->team_joined_count >= $event->max_team)
                    ){
                        return $this->sendError('The number of users participating has been maxed');
                    }
                    
                    if (!$event->team_joined->contains($teamId)) {
                        $event->team_joined()->attach([$teamId]);
                    }
                    break;
                case 'individual':
                    $event_squad_id = $request->input('event_squad_id');
                    $position_id = $request->input('position_id');
                    // check squad_id
                    if($event_squad_id){
                        $checkPermission = EventSquad::where('id', $event_squad_id)->where('event_id', $event_id)->first();
                        if(empty($checkPermission)){
                            return $this->sendError('event_squad_id does not belong to event.');
                        }
                    }

                    // check squad_id
                    if($position_id){
                        $eventPosition = EventPosition::where('id', $position_id)->where('event_id', $event_id)->first();
                        if(empty($eventPosition)){
                            return $this->sendError('position_id does not belong to event.');
                        }
                    }
                    
                    if($event->gender != 'all' && $user->gender->value != $event->gender){
                        return $this->sendError('Gender is not appropriate for the event!');
                    }

                    $_age = floor((time() - strtotime($user->birth_date)) / 31556926);
                    if(LIST_AGE[$event->age_group]['start_age'] > $_age || $_age > LIST_AGE[$event->age_group]['end_age']){
                        return $this->sendError('Age is not appropriate for the event!');
                    }

                    if(in_array($event->event_type, ['pickup', 'league'])){
                        $_listSportId = $user->sports()->get()->pluck('id')->toArray(); 
                        if(!in_array($event->sport_id, $_listSportId)){
                            return $this->sendError('Sport is not appropriate for the event!');
                        }
                    }
                    
                    // check limit event
                    if($event->event_type == 'pickup' && $event->user_joined_count >= ($event->max_team  * $event->max_player_per_team)){
                        return $this->sendError('The number of users participating has been maxed');
                    }
                    if(($event->event_type == 'session' && !$event->is_unlimit_max && $event->user_joined_count >= $event->max_player_per_team) ||
                        ($event->event_type == 'sport' && !$event->is_unlimit_max && $event->user_joined_count >= $event->max_player_per_team)
                    ){
                        return $this->sendError('The number of users participating has been maxed');
                    }
                    if($event->event_type == 'league'){
                        return $this->sendError('League game not accept individual');
                    }

                    $event
                        ->user_joined()
                        ->attach(
                            $user->id,
                            $request->only(['event_squad_id', 'position_id'])
                        );
                    break;
            }
        } catch (\Throwable $th) {
            throw $th;
        }
        
        return $this->sendResponse([], 'Success');
    }

    public function leave(EventJoinRequest $request, Event $event)
    {
        $user = $request->user();
        switch ($event->application_type) {
            case 'team':
                $validator = Validator::make($request->all(), [
                    'team_id' => 'required',
                ]);
                if ($validator->fails()) {
                    $error = $validator->errors()->first();
                    return $this->sendError($error);
                }
                $team_id = $request->input('team_id');
                $check_team = Team::find($team_id);
                if(empty($check_team)){
                    return $this->sendError('Team not found!');
                }
                // check team in event
                $existTeamInEvent = $event->team_joined()->where('id', $team_id)->first();
                if(empty($existTeamInEvent)){
                    return $this->sendError('Team not in the group!');
                }

                $help = new HelpController();
                $dataCheckPermission = $help->getPermissionRole($team_id);
                if (!in_array('team_event_management', $dataCheckPermission)) {
                    return $this->sendError('User does not have permission.');
                }

                $teamIds = $user->teams->pluck('id')->toArray();
                $event->team_joined()->detach($teamIds);
                break;
            case 'individual':
                $event->user_joined()->detach($user->id);
                break;
        }
        return $this->sendResponse([], 'Success');
    }

    protected function validateDate($date, $format = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($format, $date);
        // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
        return $d && $d->format($format) === $date;
    }
    protected function isSunday($date)
    {
        return date('N', strtotime($date)) > 6;
    }

    public function eventCode()
    {
        $uniqueStr = $this->_generateRandomString(8);
        while (Event::where('private_code', $uniqueStr)->exists()) {
            $uniqueStr = $this->_generateRandomString(8);
        }
        return $this->sendResponse(
            [
                'private_code' => $uniqueStr,
            ],
            'Success'
        );
    }

    private function _generateRandomString($length = 8)
    {
        $characters =
            '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function isOverlapSchedule(Request $request)
    {
        $res = false;
        $user = $request->user();
        $input = $request->all();

        $validator = Validator::make($request->all(), [
            'from' => 'required',
            'to' => 'required',
        ]);
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return $this->sendError($error);
        }

        $from_date = date(
            'Y-m-d H:i:s',
            $input['from'] / 1000
        );
        $to_date = date(
            'Y-m-d H:i:s',
            $input['to'] / 1000
        );
        if (strtotime($from_date) > strtotime($to_date)) {
            return $this->sendError('to date is greater than from date');
        }
        try {
            $results = $user
                ->joinedAndTeamEvents()
                ->where('events.status', EventStatusEnum::on_going())
                ->where(function ($query) use ($from_date) {
                    $query->where('start_date_time', '<', $from_date);
                    $query->where('end_date_time', '>', $from_date);
                })
                ->orWhere(function ($query) use ($to_date) {
                    $query->where('start_date_time', '<', $to_date);
                    $query->where('end_date_time', '>', $to_date);
                })
                ->first();
            if (!empty($results)) {
                return response()->json(['success' => true], 200);
            }
        } catch (\Throwable $th) {
        }
        
        return response()->json(['success' => $res], 200);
    }

    public function show($privateCode)
    {
        try {
                $results = Event::where('private_code', $privateCode)
                ->with([
                    'sport:id,name,description,max_player_per_team,min_player_per_team,is_require_choose_role',
                    'user_create:email,id,first_name,last_name',
                    'user_joined:email,id,first_name,last_name',
                    'team_joined:id,name,oganization_name,oganization_url,division,season,gender,start_age,end_age',
                    'event_squad:event_id,id,name',
                    'event_position:event_id,id,name,weight',
                ])
                ->first();
                if(!empty($results)){
                    return new EventDetailResource($results);
                }
                
            } catch (\Throwable $th) {
            }
    }   
}
