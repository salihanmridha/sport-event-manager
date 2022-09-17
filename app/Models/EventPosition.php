<?php

namespace App\Models;
use \DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventPosition extends Model
{
    use HasFactory;
    use HasAdvancedFilter;

    public $table = 'event_position';

    public $orderable = ['id', 'event_id', 'name', 'weight'];

    public $filterable = ['id', 'event_id', 'name', 'weight'];

    protected $fillable = ['id', 'event_id', 'name', 'weight'];

    protected $dates = ['created_at', 'updated_at'];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
