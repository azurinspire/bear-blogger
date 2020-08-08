<?php

namespace AzurInspire\BearBlogger\View\Components;

use AzurInspire\BearBlogger\Models\BlogPost;
use Illuminate\View\Component;

class BlogAdmin extends Component
{
    /**
     * The blog post we manage if not all
     * @var BlogPost
     */
    public $post;

    /**
     * Color of Fetch and Publish buttons
     * @var string
     */
    public $color;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(BlogPost $post = null, $color = 'indigo')
    {
        $this->post = $post;
        $this->color = $color;
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

        return view('bear-blogger::components.blog-admin');
    }
}
