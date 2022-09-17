<?php

namespace App\Models;
use \DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invite extends Model
{
    use HasFactory;
    use HasAdvancedFilter;
    use SoftDeletes;

    public $table = 'invites';

    public $orderable = [
        'id',
        'source_id',
        'source_type',
        'target_id',
        'target_type',
        'first_name',
        'last_name',
        'email',
        'response',
    ];

    public $filterable = [
        'id',
        'source_id',
        'source_type',
        'target_id',
        'target_type',
        'first_name',
        'last_name',
        'email',
        'response',
    ];

    protected $fillable = [
        'id',
        'source_id',
        'source_type',
        'target_id',
        'target_type',
        'first_name',
        'last_name',
        'email',
        'response',
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
