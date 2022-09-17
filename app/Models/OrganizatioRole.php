<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Support\HasAdvancedFilter;

class OrganizatioRole extends Model
{
    use HasFactory;
    use HasAdvancedFilter;

    public $table = 'organization_role';

    protected $fillable = [
        'code', 'name', 'creator_id', 'created_at',
        'updated_at'
    ];

    public $orderable = [
        'id',
        'code',
        'name',
        'creator_id',
        'created_at',
        'updated_at',
    ];

    public $filterable = [
        'id',
        'name',
        'code',
        'created_at',
        'updated_at',
    ];
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class);
    }
}
