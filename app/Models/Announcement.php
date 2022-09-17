<?php

namespace App\Models;

use \DateTimeInterface;
use App\Support\HasAdvancedFilter;
use Carbon\Carbon;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Announcement extends Model implements HasMedia
{
    use HasFactory;
    use HasAdvancedFilter;
    use SoftDeletes;
    use InteractsWithMedia;

    public const ANNOUNCEMENT_TYPE_SELECT = [
        'announcement' => 'Announcement',
        'news' => 'News',
    ];

    public const STATUS_SELECT = [
        'publish' => 'Save',
        'unpublish' => 'Draft',
    ];

    public $table = 'announcements';

    public $orderable = [
        'id',
        'type',
        'title',
        'about',
        'start_date',
        'end_date',
        'status',
        'creator.email',
        // 'created_at',
        // 'updated_at',
        // 'deleted_at',
    ];

    protected $filterable = [
        'id',
        'type',
        'title',
        'about',
        'start_date',
        'end_date',
        'status',
        'creator.email',
        // 'created_at',
        // 'updated_at',
        // 'deleted_at',
    ];

    protected $dates = [
        'start_date',
        'end_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'id',
        'type',
        'title',
        'about',
        'background_image',
        'start_date',
        'end_date',
        'status',
        'creator_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    // protected $casts = [
    //   'status' => StatusEnum::class . ':nullable'
    // ];

    protected $appends = ['background_image'];
    public function creator()
    {
        return $this->belongsTo(User::class);
    }

    public function getEventTypeLabelAttribute($value)
    {
        return static::STATUS_SELECT[$this->status] ?? null;
    }

    public function getApplicationTypeLabelAttribute($value)
    {
        return static::ANNOUNCEMENT_TYPE_SELECT[$this->type] ?? null;
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
        $this->attributes['start_date'] = $value
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
        $this->attributes['end_date'] = $value
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

    public function sport()
    {
        return $this->belongsTo(Sport::class);
    }

    public function getBackgroundImageAttribute()
    {
        return $this->getMedia('announcement_background_image')->map(function (
            $item
        ) {
            $media = $item->toArray();
            $media['url'] = $item->getUrl();

            return $media;
        });
    }

    public function getBGUrl()
    {
        return isset(
            $this->getMedia('announcement_background_image')->first()
                ->original_url
        )
            ? $this->getMedia('announcement_background_image')->first()
                ->original_url
            : null;
    }

    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}
