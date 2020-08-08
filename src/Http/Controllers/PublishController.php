<?php

namespace AzurInspire\BearBlogger\Http\Controllers;

use AzurInspire\BearBlogger\Actions\PublishPostAction;
use AzurInspire\BearBlogger\Models\BlogPost;
use Illuminate\Support\Facades\DB;

class PublishController
{
    public function store(BlogPost $post)
    {
        try {
            DB::connection('production')->getPdo();
        } catch (\Exception $e) {
            return redirect(request()->headers->get('referer'))
                ->with('status', 'error')
                ->with('message', 'DB not found - <i>npm run tunnel</i>');
        }

        $result = (new PublishPostAction)->execute($post);

        $categoryMessage = ($result['topics'] ? ' (' . $result['topics'] . ' topics created)' : '');

        if ($result['published']) {
            $message = 'Published! <a href="https://www.azurinspire.com/blog/' . $post->slug .'" target="_blank" class="underline text-blue-500">Preview</a>';
        } else {
            $message = 'No changes!';
        }

        return redirect(request()->headers->get('referer'))
                ->with('status', 'success')
                ->with('message', $message . $categoryMessage);
    }
}
