<?php

namespace App\Models;

use \DateTimeInterface;
use App\Enums\GenderEnum;
use App\Enums\PostTypeEnum;
use Spatie\MediaLibrary\HasMedia;
use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Team extends Model implements HasMedia
{
    use HasFactory;
    use HasAdvancedFilter;
    use SoftDeletes;
    use InteractsWithMedia;

    public const GENDER_SELECT = [
        'female' => 'Female',
        'male' => 'Male',
        'lgbt' => 'lgbt+',
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

    public $table = 'teams';

    protected $appends = ['team_avatar_image', 'team_background'];

    public $orderable = [
        'id',
        'name',
        'sport.name',
        'creator.name',
        'coach.name',
        'gender',
        'level',
        'oganization_name',
        'oganization_url',
        'division',
        'season',
        'sport_id',
        'creator_id',
        'start_age',
        'end_age',
        'level_id',
        'bio',
        'age_group',
        'org_role_id',
    ];

    public $filterable = [
        'id',
        'name',
        'sport.name',
        'creator.name',
//        'coach.name',
        'gender',
//        'level',
        'oganization_name',
        'oganization_url',
        'division',
        'season',
        'sport_id',
        'creator_id',
        'start_age',
        'end_age',
        'level_id',
        'bio',
        'age_group',
        'org_role_id',
//        'team_avatar_image',
//        'team_background',
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    protected $fillable = [
        'name',
        'sport_id',
        'creator_id',
        'gender',
        'level',
        'oganization_name',
        'oganization_url',
        'division',
        'season',
        'sport_id',
        'creator_id',
        'start_age',
        'end_age',
        'level_id',
        'bio',
        'age_group',
        'org_role_id',
        'team_avatar_image',
        'team_background',
        'deleted_at',
    ];

    protected $casts = [
        'gender' => GenderEnum::class . ':nullable',
    ];

    public function sport()
    {
        return $this->belongsTo(Sport::class);
    }
    public function team_level()
    {
        return $this->belongsTo(TeamLevel::class, 'level_id');
    }
    public function organization_role()
    {
        return $this->belongsTo(OrganizationRole::class, 'org_role_id');
    }

    public function getGenderLabelAttribute($value)
    {
        return static::GENDER_SELECT[$this->gender->value] ?? null;
    }

    public function creator()
    {
        return $this->belongsTo(User::class);
    }

    public function teamMember()
    {
        return $this->hasMany(TeamMember::class, 'team_id');
    }

    public function team_member()
    {
        return $this->hasMany(TeamMember::class, 'team_id')->where('status', 'active')->orderBy('weight', 'ASC')->orderBy('created_at', 'ASC');
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'team_member')->whereNull('team_member.deleted_at')->withTimestamps();
    }

    public function userRequests()
    {
        return $this->belongsToMany(User::class, 'team_request')->whereNull('team_request.response')
              ->withPivot('id', 'created_at');
    }
    public function usersBlocked()
    {
        return $this->belongsToMany(User::class, 'team_block');
    }

    public function requests()
    {
        return $this->hasMany(TeamMemberRequest::class, 'team_id');
    }

    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_team');
    }

    public function coach()
    {
        return $this->belongsTo(User::class);
    }

    public function getTeamAvatarImageAttribute()
    {
        return $this->getMedia('team_avatar_image')->first()?->getUrl();
    }

    public function getTeamBackgroundAttribute()
    {
        return $this->getMedia('team_background')->first()?->getUrl();
    }

    public function postsCreated()
    {
        return $this->hasMany(Post::class, 'creator_id')->where('creator_type', PostTypeEnum::team());
    }

    public function getTeamBackgroundLiveWire()
    {
        return $this->getMedia('team_background')->map(function ($item) {
            $media = $item->toArray();
            $media['url'] = $item->getUrl();

            return $media;
        });
    }
    public function getTeamAvatarLiveWire()
    {
        return $this->getMedia('team_avatar_image')->map(function ($item) {
            $media = $item->toArray();
            $media['url'] = $item->getUrl();

            return $media;
        });
    }
}
