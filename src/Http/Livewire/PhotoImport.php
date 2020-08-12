<?php

namespace AzurInspire\BearBlogger\Http\Livewire;

use AzurInspire\BearBlogger\Actions\PublishGalleryAction;
use AzurInspire\BearBlogger\Models\BlogPost;
use AzurInspire\BearBlogger\Models\Gallery;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class PhotoImport extends Component
{
    public $directory = null;
    public $files = null;
    public $photos = [];
    public $status = 'init';
    public $modelId = null;
    public $type = null;
    public $names = [];
    public $descriptions = [];
    public $heroMediaId = null;

    public function mount($model, $type)
    {
        $this->modelId = $model->id;
        $this->type = $type;
        $photos = $model->getMedia();
        $this->heroMediaId = $model->hero_media_id ?? null;

        if ($photos->isNotEmpty()) {
            $this->names = $photos->mapWithKeys(function ($i) {
                return ['id_' . $i->id => $i->name];
            })->toArray();
            $this->descriptions = $photos->mapWithKeys(function ($i) {
                return ['id_' . $i->id => $i->getCustomProperty('description')];
            })->toArray();
        }

        $this->photos = $photos->toArray();
    }

    public function updatedDirectory($directory)
    {
        if (! $directory) {
            return;
        }

        $this->files = Storage::files($directory);
    }

    public function import($file)
    {
        if ($this->type === 'blog') {
            $model = BlogPost::find($this->modelId);
        } else {
            $model = Gallery::find($this->modelId);
        }

        [$width, $height] = getimagesize(storage_path('app/' . $file));

        $photo = $model
                ->addMedia(storage_path('app/' . $file))
                ->preservingOriginal()
                ->withResponsiveImages()
                ->withCustomProperties([
                    'original_width' => $width,
                    'original_height' => $height,
                    'orientation' => $width > $height ? 'landscape' : 'portrait',
                    'description' => 'this should come from meta',
                ])->toMediaCollection();

        $this->names['id_' . $photo->id] = $photo->name;
        $this->descriptions['id_' . $photo->id] = $photo->getCustomProperty('description');

        $this->photos[] = $photo->toArray();
    }

    public function thumb($photoId)
    {
        $photo = Media::find($photoId);

        return collect($photo->getResponsiveImageUrls())->last();
    }

    public function up($mediaId)
    {
        $media = Media::find($mediaId);

        if ($media->order_column === 1) {
            return;
        }

        Media::whereModelType($media->model_type)->whereModelId($media->model_id)->whereOrderColumn($media->order_column - 1)->update([
            'order_column' => $media->order_column,
        ]);

        $media->order_column--;
        $media->save();

        $this->photos = $media->model->getMedia();
    }

    public function down($mediaId)
    {
        $media = Media::find($mediaId);

        if ($media->order_column === Media::whereModelType($media->model_type)->whereModelId($media->model_id)->max('order_column')) {
            return;
        }

        Media::whereModelType($media->model_type)->whereModelId($media->model_id)->whereOrderColumn($media->order_column + 1)->update([
            'order_column' => $media->order_column,
        ]);

        $media->order_column++;
        $media->save();

        $this->photos = $media->model->getMedia();
    }

    public function save()
    {
        if ($this->type === 'blog') {
            $model = BlogPost::find($this->modelId);
            $model->hero_media_id = $this->heroMediaId;
            $model->save();
        } else {
            $model = Gallery::find($this->modelId);
        }

        $model->getMedia()->each(function (Media $photo) {
            $photo->name = $this->names['id_' . $photo->id];
            $photo->setCustomProperty('description', $this->descriptions['id_' . $photo->id]);
            $photo->save();
        });

        $this->emit('alert', 'Saved!', 'success');
    }

    public function delete($photoId)
    {
        $media = Media::find($photoId);
        $model = $media->model;
        $media->delete();

        $this->photos = $model->getMedia();

        $this->emit('alert', 'Removed!', 'success');
    }

    public function publish()
    {
        try {
            DB::connection('production')->getPdo();
        } catch (\Exception $e) {
            $this->emit('alert', 'npm run tunnel!', 'error');

            return;
        }

        (new PublishGalleryAction)->execute($this->modelId);

        $this->emit('alert', 'Published!', 'success');
    }

    public function render()
    {
        return view('bear-blogger::livewire.photo-import', [
            'directories' => Storage::directories(config('bear-blogger.import-path')),
        ]);
    }
}
