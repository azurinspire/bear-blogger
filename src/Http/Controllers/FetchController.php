<?php

namespace AzurInspire\BearBlogger\Http\Controllers;

use AzurInspire\BearBlogger\Actions\FetchBlogPostAction;
use AzurInspire\BearBlogger\Actions\FetchNewBlogPostsAction;
use AzurInspire\BearBlogger\Models\BlogPost;

class FetchController
{
    public function store()
    {
        $inserts = (new FetchNewBlogPostsAction)->execute();

        return redirect(request()->headers->get('referer'))
            ->with('status', 'success')
            ->with('message', $inserts ? 'Inserted ' . $inserts . ' posts' : 'No new posts');
    }

    public function update(BlogPost $blogPost)
    {
        $changed = (new FetchBlogPostAction)->execute($blogPost);

        return redirect(request()->headers->get('referer'))
            ->with('status', 'success')
            ->with('message', $changed ? 'Updated!' : 'No Changes!');
    }
}
