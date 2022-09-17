<?php

namespace App\Models;

use \DateTimeInterface;
use App\Support\HasAdvancedFilter;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TeamMember extends Model
{
    use HasFactory;
    use HasAdvancedFilter;
    use SoftDeletes;

    public $table = 'team_member';

    public $orderable = [
        'id',
        'user_id',
        'team_id',
        'status',
        'jersey_number',
        'player_role',
        'weight',
    ];

    public $filterable = [
        'id',
        'user_id',
        'team_id',
        'status',
        'jersey_number',
        'player_role',
        'weight',
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    protected $fillable = [
        'id',
        'user_id',
        'team_id',
        'status',
        'jersey_number',
        'player_role',
        'weight',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function team_member()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function member_role()
    {
        return $this->belongsToMany(
            Role::class,
            'member_role',
            'member_id',
            'role_id'
        );
    }
    public function getIsOwnerAttribute()
    {
        return $this->member_role()
            ->where('title', 'Team Owner')
            ->exists();
    }
    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

    public function team_event()
    {
        return $this->belongsToMany(
            Event::class,
            'event_team',
            'team_id',
            'event_id'
        );
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
