<?php

namespace App\Models;

use App\Enums\VenueBookingStatus;
use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\InteractsWithMedia;

class VenueBooking extends Model
{
    use HasFactory;
    use InteractsWithMedia;
    use HasAdvancedFilter;

    public $table = 'venue_booking';

    protected $fillable = [
        'venue_id',
        'event_id',
        'response'
    ];

    public $orderable = [
        'id',
        'venue_id',
        'event_id',
        'response',
        'event.title',
        'created_at'
    ];

    public $filterable = [
        'id',
        'response',
        'event.title',
    ];

    protected $casts = [
        'response' => VenueBookingStatus::class
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id', 'id');
    }
}
