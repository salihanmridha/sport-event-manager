<?php

namespace App\Models;

use \DateTimeInterface;
use App\Support\HasAdvancedFilter;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TeamLevel extends Model
{
    use HasFactory;
    use HasAdvancedFilter;

    public $table = 'team_level';

    public $orderable = ['id', 'code', 'name', 'creator_id'];

    public $filterable = ['id', 'code', 'name', 'creator_id'];

    protected $dates = ['created_at', 'updated_at'];

    protected $fillable = ['id', 'code', 'name', 'creator_id'];


    public function user_create()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    public function creator()
    {
        return $this->belongsTo(User::class);
    }


}
