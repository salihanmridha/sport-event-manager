<?php

namespace App\Models;

use \DateTimeInterface;
use App\Support\HasAdvancedFilter;
use Carbon\Carbon;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Post extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    public $table = 'posts';

    protected $fillable = ['content', 'team_id', 'creator_id', 'creator_type'];

    public function user_post()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function team_post()
    {
        return $this->belongsTo(Team::class, 'id', 'creator_id');
    }
    public function bookmark_post()
    {
        return $this->hasMany(Bookmark::class, 'target_id')->where('target_type','=','post');
    }
    public function like_post()
    {
        return $this->hasMany(Like::class, 'target_id')->where('target_type','=','post');
    }
    public function comment_post()
    {
        return $this->hasMany(Comment::class, 'post_id');
    }


    public function images()
    {
        return $this->getMedia('post')->map(function ($item, $key) {
            return [
                'id' => $item['id'],
                'name' => $item['name'],
                'url' => $item['original_url'],
            ];
        });
    }
}
