<?php

namespace AzurInspire\BearBlogger\Actions;

use AzurInspire\BearBlogger\Models\BlogPost;
use BearSync\BearNote;

class FetchBlogPostAction
{
    public function execute(BlogPost $blogPost): bool
    {
        $bearNote = BearNote::find($blogPost->bear_id);

        if ((string) $bearNote->checksum === (string) $blogPost->checksum) {
            return false;
        }

        $blogPost->title = $bearNote->title;
        $blogPost->content = FetchNewBlogPostsAction::cleanContent($bearNote->content);
        $blogPost->checksum = $bearNote->checksum;
        $blogPost->is_published = false;
        $blogPost->save();

        (new LinkBlogPostCategoriesAction)->execute($bearNote, $blogPost);

        return true;
    }
}
