<?php
namespace AzurInspire\BearBlogger\Actions;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class PublishImagesAction
{
    public function execute($model)
    {
        $inserts = 0;

        $model->getMedia()->each(function ($media) use (&$inserts) {
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
                    'manipulations' => json_encode($media->manipulations),
                    'custom_properties' => json_encode($media->custom_properties),
                    'responsive_images' => json_encode($media->responsive_images),
                    'order_column' => $media->order_column,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(storage_path('app/public/' . $media->id)));

                foreach ($rii as $file) {
                    if ($file->isDir()) {
                        continue;
                    }
                    $file = str_replace('/Users/kalle/Projects/azurinspire/kallepyorala-com/storage/app/', '', $file->getPathname());
                    Storage::disk('sftp')->writeStream('/kallepyorala.com/storage/app/' . $file, Storage::readStream($file));
                }

                $inserts++;
            }
        });

        return $inserts;
    }
}
