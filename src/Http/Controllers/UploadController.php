<?php

namespace AzurInspire\BearBlogger\Http\Controllers;

use AzurInspire\BearBlogger\Models\BlogImage;
use AzurInspire\BearBlogger\Models\BlogPost;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class UploadController
{
    public function store(BlogPost $blogPost)
    {
        $mediaItem = $blogPost
            ->addMedia(request()->file('filepond'))
            ->withResponsiveImages()
            ->toMediaCollection();

        return $mediaItem->id;
    }

    public function destroy()
    {
        /** @var BlogImage */
        $mediaItem = Media::find(request()->getContent());

        $mediaItem->delete();

        return response()->noContent();
    }
}
