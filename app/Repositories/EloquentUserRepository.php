<?php

namespace App\Repositories;

use App\Http\Resources\EventResource;
use App\Http\Resources\PostResource;
use App\Interfaces\BaseRepository;
use App\Models\Event;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class EloquentUserRepository implements BaseRepository
{
    protected $model;

    public function __construct(User $user)
    {
        $this->model = $user;
    }

    public function infoContent($user, $input = [])
    {
        $limit = isset($input['perpage']) ? $input['perpage'] : 20;

        $events = $user->eventsCreated()->selectRaw('id, created_at, "event" as type');
        $union = $user->postsCreated()->selectRaw('id, created_at, "post" as type')
            ->union($events)->orderByDesc('created_at')
            ->paginate($limit);
        $sortedIds = $union->map(function ($item) {
            return $item->type.$item->id;
        })->toArray();


        $eventIds = $union->groupBy('type')->get('event')?->pluck('id')->toArray() ?? [];
        $postIds = $union->groupBy('type')->get('post')?->pluck('id')->toArray() ?? [];

        $events = Event::whereNull('deleted_at')
            ->whereIn('id', $eventIds)
            ->with([
                'user_create:email,id,first_name,last_name',
                'user_joined:email,id,first_name,last_name',
                'team_joined:id,name,oganization_name,oganization_url,division,season,gender,start_age,end_age',
            ])
            ->addSelect(DB::raw('*, "event" as dataType'))
            ->get();
        $posts = Post::whereIn('id', $postIds)
            ->addSelect(DB::raw('*, "post" as dataType'))
            ->get();

        $eventCollection = EventResource::collection($events);
        $postCollection = PostResource::collection($posts);
        // print_r($postCollection);die;
        $union = $eventCollection->merge($postCollection->collection)->sortBy(function ($item) use($sortedIds) {
            // echo $item->id;die;
            // print_r($item);die;
            // if (is_array($item)) {
            //     var_dump($item);die;
            // }
            $k = $item->dataType.$item->id;
            // echo $k;die;
            // print_r($sortedIds);die;
            $key = array_search($k, $sortedIds);
            
            return $key;
        });
        return $union->values()->all();
    }
}
