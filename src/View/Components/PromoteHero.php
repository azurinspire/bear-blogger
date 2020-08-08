<?php

namespace AzurInspire\BearBlogger\View\Components;

use AzurInspire\BearBlogger\Models\BlogPost;
use Illuminate\View\Component;

class PromoteHero extends Component
{
    /** @var string */
    public $blogPost;

    /** @var int */
    public $mediaId;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(BlogPost $blogPost, int $mediaId)
    {
        $this->blogPost = $blogPost;
        $this->mediaId = $mediaId;
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

        return view('bear-blogger::components.promote-hero');
    }
}
