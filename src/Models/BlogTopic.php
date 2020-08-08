<?php

namespace AzurInspire\BearBlogger\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class BlogTopic extends Model
{
    protected $fillable = [
        'slug',
        'name',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope('sort', static function (Builder $builder) {
            $builder->orderBy('name');
        });
    }
}
