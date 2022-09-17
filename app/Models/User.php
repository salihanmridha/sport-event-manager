<?php

namespace App\Models;

use App\Enums\GenderEnum;
use App\Enums\PostTypeEnum;
use \DateTimeInterface;
use App\Support\HasAdvancedFilter;
use Carbon\Carbon;
use Hash;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Country;
use Illuminate\Support\Arr;

class User extends Authenticatable implements HasLocalePreference, HasMedia
{
    use HasFactory;
    use HasAdvancedFilter;
    use Notifiable;
    use SoftDeletes;
    use InteractsWithMedia;
    use HasApiTokens;

    public const BIRTH_DATE_FORMAT = 'Y-m-d';

    public const MINIMUM_BIRTH_DATE = '1912-01-01';

    public $table = 'users';

    public const GENDER_SELECT = [
        'female' => 'Female',
        'male' => 'Male',
        'lgbt' => 'lgbt+',
    ];

    public const STATUS_SELECT = [
        'active' => 'Active',
        'inactive' => 'Inactive',
    ];

    public $orderable = [
        'id',
        'email',
        'email_verified_at',
        'locale',
        'first_name',
        'last_name',
        'status',
        'created_at',
        'updated_at',
        'gender',
        'birth_date',
        'phone',
        'bio',
        'is_notify_email',
        'is_notify_sms',
        'is_notify_push',
        'is_marketing',
        'ec_fullname',
        'ec_relationship',
        'ec_main_pcode',
        'ec_main_pnum',
        'ec_alt_pcode',
        'ec_alt_pnum',
        'ec_email',
    ];

    public $filterable = [
        'id',
        'email',
        'email_verified_at',
        'roles.title',
        'locale',
        'first_name',
        'last_name',
        'status',
        'created_at',
        'updated_at',
        'gender',
        'birth_date',
        'phone',
        'bio',
        'ec_fullname',
        'ec_relationship',
        'ec_main_pcode',
        'ec_main_pnum',
        'ec_alt_pcode',
        'ec_alt_pnum',
        'ec_email',
    ];

    protected $hidden = ['remember_token', 'password'];

    protected $fillable = [
        'email',
        'password',
        'locale',
        'first_name',
        'last_name',
        'status',
        'gender',
        'birth_date',
        'phone',
        'bio',
        'avatar',
        'background_image',
        'phone_code',
        'phone',
        'country_id',
        'phone_code',
        'currency_id',
        'provider_name',
        'provider_id',
        'ec_fullname',
        'ec_relationship',
        'ec_main_pcode',
        'ec_main_pnum',
        'ec_alt_pcode',
        'ec_alt_pnum',
        'ec_email',
        'is_notify_email',
        'is_notify_sms',
        'is_notify_push',
        'is_marketing',
    ];

    protected $appends = ['avatar', 'background_image', 'name'];

    protected $dates = [
        'email_verified_at',
        'created_at',
        'updated_at',
        'deleted_at',
        'birth_date',
        'send_email_verification_code_at',
        'birth_date',
    ];

    protected $casts = [
        'gender' => GenderEnum::class . ':nullable',
        'is_notify_email' => 'boolean',
        'is_notify_sms' => 'boolean',
        'is_notify_push' => 'boolean',
        'is_marketing' => 'boolean',
    ];

    public function getIsAdminAttribute()
    {
        return $this->roles()
            ->where('title', 'Admin')
            ->exists();
    }

    public function getIsVenueAttribute()
    {
        return $this->roles()
            ->where('title', 'Venue Manager')
            ->exists();
    }

    public function scopeAdmins()
    {
        return $this->whereHas('roles', fn($q) => $q->where('title', 'Admin'));
    }

    public function scopeUsers()
    {
        return $this->whereHas('roles', fn($q) => $q->where('title', 'User'));
    }

    public function scopeVenues()
    {
        return $this->whereHas('roles', fn($q) => $q->where('title', 'Venue Manager'));
    }

    public function preferredLocale()
    {
        return $this->locale;
    }

    public function getEmailVerifiedAtAttribute($value)
    {
        return $value
            ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(
                config('project.datetime_format')
            )
            : null;
    }

    public function getStatusLabelAttribute($value)
    {
        return static::STATUS_SELECT[$this->status] ?? null;
    }

    public function setPasswordAttribute($input)
    {
        if ($input) {
            $this->attributes['password'] = Hash::needsRehash($input)
                ? Hash::make($input)
                : $input;
        }
    }

    public function events()
    {
        return $this->belongsToMany(Event::class, 'user_event');
    }

    public function joinedAndTeamEvents()
    {
        $teamIds = $this->memberTeams()->get()->pluck('id')->toArray();
        $userId = $this->id;
        return Event::leftJoin('user_event', function ($q)  {
            $q->on('events.id', 'user_event.event_id');

        })
        ->leftJoin('event_team', function ($q)  {
            $q->on('events.id', 'event_team.event_id');
        })->where(function($q) use($userId, $teamIds) {
            $q->where('user_event.user_id', $userId)->orWhereIn('event_team.team_id', $teamIds);
        })
        ->select('events.*')->distinct();
    }

    public function eventsCreated()
    {
        return $this->hasMany(Event::class, 'creator_id');
    }
    public function postsCreated()
    {
        return $this->hasMany(Post::class, 'creator_id')->where('creator_type', PostTypeEnum::user());
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function sports()
    {
        return $this->belongsToMany(Sport::class);
    }

    public function venue()
    {
        return $this->hasOne(Venue::class, 'owner_id', 'id');
    }

    public function joinedEvents()
    {
        return $this->belongsToMany(Event::class, 'user_event');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        // $thumbnailWidth  = 50;
        // $thumbnailHeight = 50;

        // $thumbnailPreviewWidth  = 120;
        // $thumbnailPreviewHeight = 120;

        // $this->addMediaConversion('thumbnail')
        // ->width($thumbnailWidth)
        // ->height($thumbnailHeight)
        // ->fit('crop', $thumbnailWidth, $thumbnailHeight);
        // $this->addMediaConversion('preview_thumbnail');
        // ->width($thumbnailPreviewWidth)
        // ->height($thumbnailPreviewHeight)
        // ->fit('crop', $thumbnailPreviewWidth, $thumbnailPreviewHeight);
    }

    public function getECrelationship()
    {
        return $this->hasOne(
            ContactRelationship::class,
            'id',
            'ec_relationship'
        );
    }
    public function getAvatarAttribute()
    {
        return $this->getMedia('user_avatar')->first()?->getUrl();
    }

    public function getBackgroundImageAttribute()
    {
        return $this->getMedia('user_background_image')->first()?->getUrl();
    }
    public function getGenderLabelAttribute($value)
    {
        return static::GENDER_SELECT[$this->gender->value] ?? null;
    }
    public function getBirthDateAttribute($value)
    {
        return $value
            ? Carbon::parse($value)->format(config('project.date_format'))
            : null;
    }
    public function setBirthDateAttribute($value)
    {
        $this->attributes['birth_date'] = $value
            ? Carbon::createFromFormat(
                config('project.date_format'),
                $value
            )->format('Y-m-d')
            : null;
    }
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public static function createWithAttributes(array $attributes): self
    {
        if (!empty($attributes['password'])) {
            $attributes['password'] = bcrypt($attributes['password']);
        }
        $user = self::create($attributes);

        if (!empty($attributes['sport_ids'])) {
            $user->sports()->attach($attributes['sport_ids']);
        }
        return $user;
    }

    public function teams()
    {
        return $this->hasMany(Team::class, 'creator_id');
    }

    public function memberTeams()
    {
        return $this->belongsToMany(Team::class, 'team_member')->whereNull('team_member.deleted_at')->withTimestamps();
    }

    public function permission()
    {
        return $this->belongsToMany(Permission::class, 'team_member');
    }

    public function team_members()
    {
        return $this->belongsTo(TeamMember::class, 'id','user_id');
    }
    public function getAvatarUrl()
    {
        return isset($this->getMedia('user_avatar')->first()->original_url)
            ? $this->getMedia('user_avatar')->first()->original_url
            : null;
    }
    public function getBGUrl()
    {
        return isset(
            $this->getMedia('user_background_image')->first()->original_url
        )
            ? $this->getMedia('user_background_image')->first()->original_url
            : null;
    }

    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getImageId(string $collection)
    {
        return isset($this->getMedia($collection)->first()->id)
            ? $this->getMedia($collection)->first()->id
            : null;
    }

    public function getBackgroundImageLiveWire()
    {
        return $this->getMedia('user_background_image')->map(function ($item) {
            $media = $item->toArray();
            $media['url'] = $item->getUrl();

            return $media;
        });
    }
    public function getAvatarLiveWire()
    {
        return $this->getMedia('user_avatar')->map(function ($item) {
            $media = $item->toArray();
            $media['url'] = $item->getUrl();

            return $media;
        });
    }

    public function getAgeAttribute()
    {
        return Carbon::parse($this->attributes['birth_date'])->age;
    }
    public function user_interections()
    {
        return $this->hasMany(Interaction::class, 'first_id');
    }
}
