<?php

namespace App\Models;

use \DateTimeInterface;
use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Role extends Model
{
    use HasFactory;
    use HasAdvancedFilter;
    use SoftDeletes;

    public $table = 'roles';

    public $orderable = [
        'id',
        'title',
        'type',
    ];

    public $filterable = [
        'id',
        'title',
        'permissions.title',
        'type',
    ];

    protected $fillable = [
        'title',
        'type',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public static function getCmsRoles() {
        $allow_type = array();
        try {
          //code...
          $rs_role = DB::table('roles')->select('id')
          ->where('type', 0)
          ->where('title', '!=', 'User')->get()->toArray();
          foreach($rs_role as $item) {
            if (isset($item->id) && !empty($item->id)) {
              $allow_type[] = $item->id;
            }
          } 
        } catch (\Throwable $th) {
          //return $th->getMessage();
        }

        return $allow_type;
    }
}
