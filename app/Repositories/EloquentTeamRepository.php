<?php

namespace App\Repositories;

use App\Http\Resources\EventResource;
use App\Http\Resources\PostResource;
use App\Interfaces\BaseRepository;
use App\Models\Event;
use App\Models\Post;
use App\Models\Team;
use Illuminate\Support\Facades\DB;

class EloquentTeamRepository implements BaseRepository
{
    protected $model;

    public function __construct(Team $team)
    {
        $this->model = $team;
    }

    public function infoContent($team, $input = [])
    {
        $limit = isset($input['perpage']) ? $input['perpage'] : 20;

        // $events = $user->eventsCreated()->selectRaw('id, created_at, "event" as type');
        $union = $team->postsCreated()->selectRaw('id, created_at, "post" as type')
            // ->union($events)
            ->orderByDesc('created_at')
            ->paginate($limit)
            ->groupBy('type');


        // $eventIds = $union->get('event')?->pluck('id')->toArray() ?? [];
        $postIds = $union->get('post')?->pluck('id')->toArray() ?? [];

        // $events = Event::whereNull('deleted_at')
        //     ->whereIn('id', $eventIds)
        //     ->with([
        //         'user_create:email,id,first_name,last_name',
        //         'user_joined:email,id,first_name,last_name',
        //         'team_joined:id,name,oganization_name,oganization_url,division,season,gender,start_age,end_age',
        //     ])
        //     ->addSelect(DB::raw('*, "event" as dataType'))
        //     ->get();
        $posts = Post::whereIn('id', $postIds)
            ->addSelect(DB::raw('*, "post" as dataType'))
            ->get();

        // $eventCollection = EventResource::collection($events);
        $postCollection = PostResource::collection($posts);
        $union = $postCollection;
        return $union;
    }
}
