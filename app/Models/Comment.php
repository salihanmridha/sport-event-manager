<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Support\HasAdvancedFilter;

class Comment extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasAdvancedFilter;

    public $table = 'comments';

    protected $fillable = [
        'id',
        'author_id',
        'author_type',
        'post_id',
        'reply_id',
        'content',
        'post_by',
    ];

    public $orderable = [
        'id',
        'author_id',
        'author_type',
        'post_id',
        'reply_id',
        'content',
        'post_by',
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    public $filterable = [
        'id',
        'author_id',
        'author_type',
        'post_id',
        'reply_id',
        'content',
        'post_by',
    ];

    /**
     * @return BelongsTo
     */
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
