<?php

namespace App\Models;

use App\Enums\VenueStatus;
use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Court extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasAdvancedFilter;

    public $table = 'courts';

    protected $fillable = [
        'id',
        'venue_id',
        'sport_id',
        'name',
        'price',
        'weight',
        'status',
        'creator_id',
    ];

    public $orderable = [
        'id',
        'venue_id',
        'sport_id',
        'name',
        'price',
        'weight',
        'status',
        'creator_id',
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    public $filterable = [
        'id',
        'venue_id',
        'sport_id',
        'name',
        'price',
        'weight',
        'status',
        'creator_id',
    ];

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
    public function sports(): BelongsTo
    {
        return $this->belongsTo(Sport::class, 'sport_id', 'id');
    }
}
