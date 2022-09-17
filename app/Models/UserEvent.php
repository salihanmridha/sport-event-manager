<?php

namespace App\Models;
use \DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserEvent extends Model
{
    use HasFactory;
    use HasAdvancedFilter;
    // use SoftDeletes;

    public $table = 'user_event';

    public $orderable = [
        'event_id',
        'user_id',
        'position_id',
        'event_squad_id',
    ];

    public $filterable = [
        'event_id',
        'user_id',
        'position_id',
        'event_squad_id',
    ];

    protected $fillable = [
        'event_id',
        'user_id',
        'position_id',
        'event_squad_id',
    ];

    protected $dates = ['created_at', 'updated_at'];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function user_event()
    {
        return $this->belongsTo(self::class, 'event_id');
    }
}
