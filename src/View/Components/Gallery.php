<?php

namespace AzurInspire\BearBlogger\View\Components;

use AzurInspire\BearBlogger\Models\BlogPost;
use Illuminate\View\Component;

class Gallery extends Component
{
    /** @var BlogPost|Gallery */
    public $model;

    public $photos;

    public $edit = false;

    public $type;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($model)
    {
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
        if (config('app.env') === 'local') {
            $this->edit = true;
        }

        if (! is_null(request()->input('hide'))) {
            $this->edit = false;
        }

        return view('bear-blogger::components.gallery');
    }
}
