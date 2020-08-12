<?php

namespace AzurInspire\BearBlogger\View\Components;

use AzurInspire\BearBlogger\Models\BlogPost;
use AzurInspire\BearBlogger\Models\Gallery as GalleryModel;
use Illuminate\View\Component;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Gallery extends Component
{
    /** @var BlogPost|Gallery */
    public $model;

    public $photos;

    public $edit = false;

    public $type;

    public $recent = false;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($model, $recent = false)
    {
        $this->recent = $recent;

        if ($this->recent) {
            $this->type = 'gallery';
            $this->photos = Media::query()
                ->whereModelType(GalleryModel::class)
                ->orderBy('created_at', 'DESC')
                ->limit(20)
                ->get();

            return;
        }

        $this->model = $model;

        if ($model instanceof BlogPost) {
            $this->type = 'blog';
        } else {
            $this->type = 'gallery';
        }

        $this->photos = $model->getMedia();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        if (config('app.env') === 'local' && ! $this->recent) {
            $this->edit = true;
        }

        if (! is_null(request()->input('hide'))) {
            $this->edit = false;
        }

        return view('bear-blogger::components.gallery');
    }
}
