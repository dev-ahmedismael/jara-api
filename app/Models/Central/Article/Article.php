<?php

namespace App\Models\Central\Article;

use App\Models\Tenant\Comment\Comment;
use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Article extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, Filterable;

    protected $fillable = ['title', 'content', 'author', 'enable_comments'];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('article_images')->singleFile();
    }
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
