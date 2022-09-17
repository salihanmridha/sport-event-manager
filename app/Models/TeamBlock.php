<?php

namespace App\Models;

use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TeamBlock extends Model
{
    use HasFactory;
    use HasAdvancedFilter;

    public $table = 'team_block';

    protected $fillable = ['team_id', 'user_id'];

    public $orderable = [
        'team_id',
        'user_id',
    ];

    public $filterable = [
        'team_id',
        'user_id',
    ];

    protected $dates = ['created_at', 'updated_at'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }
}
