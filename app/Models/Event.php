<?php

namespace App\Models;

use App\Enums\ApplicationTypeEnum;
use App\Enums\EventStatusEnum;
use App\Enums\GenderEnum;
use \DateTimeInterface;
use App\Support\HasAdvancedFilter;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Event extends Model implements HasMedia
{
    use HasFactory;
    use HasAdvancedFilter;
    use SoftDeletes;
    use InteractsWithMedia;

    public const APPLICATION_TYPE_SELECT = [
        'individual' => 'Individual Application',
        'team' => 'Team Application',
    ];

    public const EVENT_TYPE_SELECT = [
        'pickup' => 'Pick-up Game',
        'league' => 'League Game',
        'sport' => 'Sports Event',
        'session' => 'Session',
    ];

    public const GENDER_SELECT = [
        'female' => 'Female',
        'male' => 'Male',
        'lgbt' => 'LGBT+',
        'all' => 'All Gender',
    ];

    public const PRIVACY_SELECT = [
        true => 'Public Event',
        false => 'Private Event',
    ];

    public const AGE_SELECT = [
        0 => 'All Age Group (0-99)',
        1 => 'Babies (0-2)',
        2 => 'Children (3-12)',
        3 => 'Teenager (13-19)',
        4 => 'Young Adult (20-30)',
        5 => 'Middle-Aged Adults (31-45)',
        6 => 'Old Adults (46 and above)',
    ];

    public const OWNERSHIP_TYPE_SELECT = [
        'palaro' => 'PALARO',
        'user' => 'User',
    ];

    public const EVENT_TYPE_VALUE = ['pickup', 'league', 'sport', 'session'];

    public $table = 'events';

    public $orderable = [
        'id',
        'event_type',
        'sport.name',
        'max_team',
        'max_player_per_team',
        'application_type',
        'description',
        'start_date_time',
        'end_date_time',
        'lat',
        'long',
        'is_paid',
        'gender',
        'location',
        'title',
        'caption',
        'start_age',
        'end_age',
        'fee',
        'is_public',
        'status',
        'is_set_role',
        'event_type',
        'description',
        'is_unlimit_max',
        'about',
        'mechanics',
        'created_at',
        'updated_at',
        'deleted_at',
        'age_group',
        'private_code',
        'creator_id',
        'sport_id',
        'creator_id',
    ];

    protected $appends = ['upload_photo'];

    protected $filterable = [
        'id',
        'event_type',
        'sport.name',
        'max_team',
        'max_player_per_team',
        'application_type',
        'description',
        'start_date_time',
        'end_date_time',
        'lat',
        'long',
        'is_paid',
        'gender',
        'location',
        'title',
        'caption',
        'start_age',
        'end_age',
        'fee',
        'is_public',
        'status',
        'is_set_role',
        'event_type',
        'description',
        'is_unlimit_max',
        'about',
        'mechanics',
        'created_at',
        'updated_at',
        'deleted_at',
        'age_group',
        'private_code',
        'creator_id',
        'sport_id',
        'event_ownership',
        'creator_id',
    ];

    protected $dates = [
        'start_date_time',
        'end_date_time',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'event_type',
        'sport_id',
        'max_team',
        'max_player_per_team',
        'application_type',
        'description',
        'start_date_time',
        'end_date_time',
        'lat',
        'long',
        'is_paid',
        'gender',
        'location',
        'title',
        'caption',
        'start_age',
        'end_age',
        'fee',
        'is_public',
        'status',
        'is_set_role',
        'event_type',
        'description',
        'is_unlimit_max',
        'about',
        'mechanics',
        'created_at',
        'updated_at',
        'deleted_at',
        'age_group',
        'private_code',
        'creator_id',
        'upload_photo',
        'event_ownership',
    ];

    protected $casts = [
        'status' => EventStatusEnum::class . ':nullable',
        'gender' => GenderEnum::class . ':nullable',
        'is_set_role' => 'boolean',
        'is_paid' => 'boolean',
    ];

    public function getEventTypeLabelAttribute($value)
    {
        return static::EVENT_TYPE_SELECT[$this->event_type] ?? null;
    }

    public function sport()
    {
        return $this->belongsTo(Sport::class);
    }

    public function user_create()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function user_joined()
    {
        return $this->belongsToMany(User::class, 'user_event')
            ->withPivot('position_id', 'event_squad_id')
            ->withTimestamps();
    }
    public function team_joined()
    {
        return $this->belongsToMany(Team::class, 'event_team');
    }

    public function event_squad()
    {
        return $this->hasMany(EventSquad::class, 'event_id');
    }
    public function event_position()
    {
        return $this->hasMany(EventPosition::class, 'event_id')->orderBy(
            'weight'
        );
    }
    // public function event_team()
    // {
    //     return $this->hasMany('event_team', 'event_id');
    // }

    public function getApplicationTypeLabelAttribute($value)
    {
        return static::APPLICATION_TYPE_SELECT[$this->application_type] ?? null;
    }

    public function getStartDateTimeAttribute($value)
    {
        return $value
            ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(
                config('project.datetime_format')
            )
            : null;
    }

    public function setStartDateTimeAttribute($value)
    {
        $this->attributes['start_date_time'] = $value
            ? Carbon::createFromFormat(
                config('project.datetime_format'),
                $value
            )->format('Y-m-d H:i:s')
            : null;
    }

    public function getEndDateTimeAttribute($value)
    {
        return $value
            ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(
                config('project.datetime_format')
            )
            : null;
    }

    public function setEndDateTimeAttribute($value)
    {
        $this->attributes['end_date_time'] = $value
            ? Carbon::createFromFormat(
                config('project.datetime_format'),
                $value
            )->format('Y-m-d H:i:s')
            : null;
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function images()
    {
        return $this->getMedia('event')->map(function ($item, $key) {
            return [
                'id' => $item['id'],
                'name' => $item['name'],
                'url' => $item['original_url'],
            ];
        });
    }

    public function list_images_id()
    {
        return $this->getMedia('event')->pluck('id');
    }

    /**
     * @param $status
     * @return mixed
     */
    public function countEventByStatus($status): mixed
    {
        return $this->where('status', $status)->count();
    }

    /**
     * @return BelongsToMany
     */
    public function usersJoined(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_event')
            ->withPivot('position_id', 'event_squad_id', 'user_id')
            ->withTimestamps();
    }

    /**
     * @return BelongsToMany
     */
    public function teamsJoined(): BelongsToMany
    {
        return $this->belongsToMany(Team::class);
    }

    /**
     * @return BelongsToMany
     */
    public function usersInvited(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'invites',
            'source_id',
            'target_id'
        )
            ->wherePivot('target_type', 'user')
            ->withPivot('response');
    }

    /**
     *
     * @return BelongsToMany
     */
    public function teamsInvited(): BelongsToMany
    {
        return $this->belongsToMany(
            Team::class,
            'invites',
            'source_id',
            'target_id'
        )
            ->wherePivot('target_type', 'team')
            ->withPivot('response');
    }

    public function getUploadPhotoAttribute()
    {
        return $this->getMedia('upload_photo')->map(function ($item) {
            $media = $item->toArray();
            $media['url'] = $item->getUrl();

            return $media;
        });
    }

    public function getListPlayer($id)
    {
        return $this->select(
            'users.id as user_id',
            'users.first_name',
            'users.last_name',
            'users.id as user_id',
            'events.id as event_id',
            'events.title',
            'events.id as event_id',
            'events.event_type',
            'user_event.created_at',
            'event_position.name as position_name',
            'event_squad.name as squad_name'
        )
            ->join('user_event', 'events.id', 'user_event.event_id')
            ->join(
                'event_position',
                'user_event.position_id',
                'event_position.id'
            )
            ->join('event_squad', 'user_event.event_squad_id', 'event_squad.id')
            ->join('users', 'users.id', 'user_event.user_id')
            ->where('events.id', $id)
            ->paginate(20);
    }
}
