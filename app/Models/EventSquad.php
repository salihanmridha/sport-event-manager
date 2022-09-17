<?php

namespace App\Models;
use \DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventSquad extends Model
{
    use HasFactory;
    use HasAdvancedFilter;

    public $table = 'event_squad';

    public $orderable = ['id', 'event_id', 'name'];

    public $filterable = ['id', 'event_id', 'name'];

    protected $fillable = ['id', 'event_id', 'name'];

    protected $dates = ['created_at', 'updated_at'];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
