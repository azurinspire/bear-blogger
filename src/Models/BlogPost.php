<?php

namespace AzurInspire\BearBlogger\Models;

use AzurInspire\BearBlogger\Casts\MarkdownToHtml;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class BlogPost extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'bear_id',
        'checksum',
        'slug',
        'title',
        'content',
        'publish_at',
        'is_published',
    ];

    protected $casts = [
        'content' => MarkdownToHtml::class,
    ];

    protected static function booted(): void
    {
        static::addGlobalScope('published', static function (Builder $builder) {
            $builder->where('publish_at', '<', now());
        });
    }

    public function topics()
    {
        return $this->belongsToMany(BlogTopic::class);
    }

    public function images()
    {
        return $this->hasMany(BlogImage::class);
    }

    public function heroImage($type = 'responsive')
    {
        $heroImage = Media::find($this->hero_media_id);

        if (! $heroImage) {
            return;
        }

        if ($type === 'small') {
            return collect($heroImage->getResponsiveImageUrls())->last();
        }

        if ($type === 'medium') {
            return collect($heroImage->getResponsiveImageUrls())->get(6);
        }


        return $heroImage;
    }
}
