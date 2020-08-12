<?php

namespace AzurInspire\BearBlogger\Actions;

use AzurInspire\BearBlogger\Models\Gallery;
use Illuminate\Support\Facades\DB;

class PublishGalleryAction
{
    /** @var Gallery */
    public function execute($galleryId)
    {
        $gallery = Gallery::find($galleryId);
        $remoteGallery = DB::connection('production')->table('galleries')->where('id', $galleryId)->first();

        if ($remoteGallery) {
            $this->updateGallery($gallery);
        } else {
            $this->insertGallery($gallery);
        }

        (new PublishImagesAction)->execute($gallery);
    }

    protected function updateGallery($gallery)
    {
        DB::connection('production')->table('galleries')->where('id', $gallery->id)->update([
            'name' => $gallery->name,
            'slug' => $gallery->slug,
        ]);
    }

    protected function insertGallery(Gallery $gallery)
    {
        DB::connection('production')->table('galleries')->insert([
            'id' => $gallery->id,
            'name' => $gallery->name,
            'slug' => $gallery->slug,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
