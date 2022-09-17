<?php

namespace App\Models;

use \DateTimeInterface;
use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookMark extends Model
{
    use HasFactory;
    use HasAdvancedFilter;

    public $table = 'bookmark';

    public $orderable = ['user_id', 'target_id', 'target_type'];

    public $filterable = ['user_id', 'target_id', 'target_type'];

    public $hidden = ['id'];

    protected $fillable = ['user_id', 'target_id', 'target_type'];

    protected $dates = ['created_at', 'updated_at'];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    public function event()
    {
        return $this->belongsTo(Event::class, 'id', 'target_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function user_bookmark()
    {
        return $this->belongsTo(User::class, 'target_id');
    }
    public function post_bookmark()
    {
        return $this->belongsTo(Post::class, 'target_id');
    }
    public function event_bookmark()
    {
        return $this->belongsTo(Event::class, 'target_id');
    }
    public function venue_bookmark()
    {
        return $this->belongsTo(Venue::class, 'target_id');
    }
}
