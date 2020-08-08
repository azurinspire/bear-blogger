<?php

namespace AzurInspire\BearBlogger\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

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
