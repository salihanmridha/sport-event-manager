<?php

namespace App\Models;

use \DateTimeInterface;
use App\Support\HasAdvancedFilter;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberRole extends Model
{
    use HasFactory;
    use HasAdvancedFilter;
    public $timestamps = false;
    public $table = 'member_role';
    public $orderable = ['member_id', 'role_id'];
    public $filterable = ['member_id', 'role_id'];
    protected $fillable = ['member_id', 'role_id'];

    public function permission_role()
    {
        return $this->belongsToMany(
            Permission::class,
            'permission_role',
            'role_id',
            'permission_id'
        );
    }
}
