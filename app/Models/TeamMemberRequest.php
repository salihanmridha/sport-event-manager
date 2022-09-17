<?php

namespace App\Models;

use \DateTimeInterface;
use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamMemberRequest extends Model
{
    use HasFactory;
    use HasAdvancedFilter;

    public $table = 'team_request';

    public $orderable = [
        'id',
        'user_id',
        'team_id',
        'response',
    ];

    public $filterable = [
        'id',
        'user_id',
        'team_id',
        'response'
    ];

    protected $dates = ['created_at', 'updated_at'];

    protected $fillable = [
        'id',
        'user_id',
        'team_id',
        'response',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
