<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class BlogPost extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'meta_description',
        'excerpt',
        'content',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function scopePublished(Builder $query): Builder
    {
        return $query->whereNotNull('published_at')->where('published_at', '<=', now());
    }
}
