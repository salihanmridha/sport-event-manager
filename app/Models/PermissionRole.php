<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermissionRole extends Model
{
    use HasFactory;

    public $table = 'permission_role'; 
    public $timestamps = false;

    public $orderable = [
      'role_id',
      'permission_id',
    ];

    public $filterable = [
      'role_id',
      'permission_id',
    ];

    protected $fillable = [
      'role_id',
      'permission_id',
    ];
}
