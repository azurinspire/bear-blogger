<?php
namespace AzurInspire\BearBlogger\Actions;

use AzurInspire\BearBlogger\Models\BlogPost;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class PublishMediaAction
{
    public function execute(BlogPost $post)
    {
        $inserts = 0;

        $post->getMedia()->each(function ($media) use ($post, &$inserts) {
            if (DB::connection('production')->table('media')->where('id', $media->id)->doesntExist()) {
                DB::connection('production')->table('media')->insert([
                    'id' => $media->id,
                    'model_type' => $media->model_type,
                    'model_id' => $media->model_id,
                    'uuid' => $media->uuid,
                    'collection_name' => $media->collection_name,
                    'name' => $media->name,
                    'file_name' => $media->file_name,
                    'mime_type' => $media->mime_type,
                    'disk' => $media->disk,
                    'conversions_disk' => $media->conversions_disk,
                    'size' => $media->size,
                    'manipulations' => $media->manipulations,
                    'custom_properties' => $media->custom_properties,
                    'responsive_images' => $media->responsive_images,
                    'order_column' => $media->order_column,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $files = File::files(public_path($media->id));
                dd($files);
                Storage::disk('FTP')->writeStream('new/file1.jpg', Storage::readStream('old/file1.jpg'));


                $inserts++;
            }
        });

        return $inserts;
    }
}
