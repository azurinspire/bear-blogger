<?php

namespace AzurInspire\BearBlogger\Http\Controllers;

use AzurInspire\BearBlogger\Models\BlogPost;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class PromoteController
{
    public function update(BlogPost $blogPost, Media $media)
    {
        $blogPost->hero_media_id = $media->id;
        $blogPost->save();

        return redirect()->back();
    }
}
