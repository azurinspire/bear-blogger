<?php

namespace AzurInspire\BearBlogger\Http\Controllers;

use AzurInspire\BearBlogger\Models\BlogImage;
use AzurInspire\BearBlogger\Models\BlogPost;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class UploadController
{
    public function store(BlogPost $blogPost)
    {
        [$width, $height] = getimagesize(request()->file('filepond'));

        $order = session('order') + 1;
        session(['order' => $order]);

        $mediaItem = $blogPost
            ->addMedia(request()->file('filepond'))
            ->withResponsiveImages()
            ->withCustomProperties([
                'original_width' => $width,
                'original_height' => $height,
                'orientation' => $width > $height ? 'landscape' : 'portrait',
            ])->withAttributes([
                'order_column' => $order,
            ])
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
