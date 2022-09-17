<?php

namespace App\Models;

use App\Enums\VenueStatus;
use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Venue extends Model implements HasMedia
{
    use HasFactory;
    use SoftDeletes;
    use InteractsWithMedia;
    use HasAdvancedFilter;

    public $table = 'venues';

    protected $fillable = [
        'type',
        'name',
        'address',
        'lat',
        'long',
        'email',
        'phone_code',
        'phone_number',
        'bio',
        'status',
        'start_open_time',
        'end_open_time',
        'rules',
        'safety',
        'popularity',
        'start_price',
        'end_price',
    ];

    public $orderable = [
        'id',
        'name',
        'address',
        'phone_code',
        'phone_number',
        'email',
        'owner.email',
        'country.name',
    ];

    public $filterable = [
        'id',
        'name',
        'address',
        'phone_code',
        'phone_number',
        'email',
        'owner.email',
        'country.name',
    ];

    protected $casts = [
        'status' => VenueStatus::class,
    ];

    protected $appends = ['upload_photo', 'pictures'];

    public function getUploadPhotoAttribute()
    {
        return $this->getMedia('upload_photo')->map(function ($item) {
            $media = $item->toArray();
            $media['url'] = $item->getUrl();

            return $media;
        });
    }


    public function images()
    {
        return $this->getMedia('venue')->map(function ($item, $key) {
            return [
                'id' => $item['id'],
                'name' => $item['name'],
                'url' => $item['original_url'],
            ];
        });
    }

    public function list_images_id()
    {
        return $this->getMedia('venue')->pluck('id');
    }

    /**
     * @return BelongsTo
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id', 'id');
    }

    public function sports()
    {
        return $this->belongsToMany(Sport::class, 'venue_sport');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(VenueType::class, 'type');
    }

    public function court()
    {
        return $this->hasMany(Court::class, 'venue_id')->where('status', VenueStatus::active());
    }

    public function getBackground()
    {
        return $this->getMedia('upload_photo')->first()?->getUrl();
    }

    public function getPicturesAttribute()
    {
        return $this->getMedia('banner')->map(function ($item, $key) {
            $media = $item->toArray();
            $media['url'] = $item->getUrl();

            return $media;
        });
    }

    public function workdays()
    {
        return $this->belongsToMany(WeekDay::class, 'venue_workday', 'venue_id', 'workday_id');
    }

    /**
     * @return BelongsToMany
     */
    public function weekdays(): BelongsToMany
    {
        return $this->belongsToMany(
            WeekDay::class,
            'venue_workday',
            'venue_id',
            'workday_id');
    }
}
