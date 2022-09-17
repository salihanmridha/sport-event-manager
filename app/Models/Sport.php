<?php

namespace App\Models;

use \DateTimeInterface;
use App\Support\HasAdvancedFilter;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Sport extends Model implements HasMedia
{
    use HasFactory;
    use HasAdvancedFilter;
    use SoftDeletes;
    use InteractsWithMedia;

    public const IS_REQUIRE_CHOOSE_ROLE_RADIO = [
        '0' => 'No',
        '1' => 'Yes',
    ];

    public $table = 'sports';

    public $orderable = [
        'id',
        'code',
        'name',
        'description',
        'creator.name',
        'max_player_per_team',
        'min_player_per_team',
        'is_require_choose_role',
        'updated_at',
    ];

    public $filterable = [
        'id',
        'code',
        'name',
        'description',
        'creator.name',
        'max_player_per_team',
        'min_player_per_team',
        'is_require_choose_role',
        'updated_at',
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    protected $fillable = [
        'code',
        'name',
        'description',
        'creator_id',
        'max_player_per_team',
        'min_player_per_team',
        'is_require_choose_role',
        'icon',
    ];
    protected $appends = ['icon'];

    public function creator()
    {
        return $this->belongsTo(User::class);
    }

    public function getIsRequireChooseRoleLabelAttribute($value)
    {
        return static::IS_REQUIRE_CHOOSE_ROLE_RADIO[
            $this->is_require_choose_role
        ] ?? null;
    }

    public function getUpdatedAtAttribute($value)
    {
        return $value
            ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(
                config('project.datetime_format')
            )
            : null;
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function registerMediaConversions(Media $media = null): void
    {
    }

    public function getIconAttribute()
    {
        // return $this->getMedia('sport')->map(function ($item) {
        //     $media = $item->toArray();
        //     $media['url'] = $item->getUrl();

        //     return $media;
        // });
        return isset($this->getMedia('sport')->first()->original_url)
            ? $this->getMedia('sport')->first()->original_url
            : null;
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function getIcon()
    {
        return isset($this->getMedia('sport')->first()->original_url)
            ? $this->getMedia('sport')->first()->original_url
            : null;
    }
    public function getIconLiveWire()
    {
        return $this->getMedia('sport')->map(function ($item) {
            $media = $item->toArray();
            $media['url'] = $item->getUrl();

            return $media;
        });
    }
}
