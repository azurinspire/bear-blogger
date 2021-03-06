<?php

namespace AzurInspire\BearBlogger\View\Components;

use AzurInspire\BearBlogger\Models\BlogPost;
use Illuminate\View\Component;

class ImageUploader extends Component
{
    /** @var BlogPost */
    public $post;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(BlogPost $post)
    {
        $this->post = $post;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        if (! is_null(request()->input('hide'))) {
            return '';
        }

        if (config('app.env') !== 'local') {
            return '';
        }

        return view('bear-blogger::components.image-uploader');
    }
}
