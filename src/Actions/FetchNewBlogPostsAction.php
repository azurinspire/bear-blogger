<?php

namespace AzurInspire\BearBlogger\Actions;

use AzurInspire\BearBlogger\Models\BlogPost;
use BearSync\BearTag;
use Illuminate\Support\Str;
use InvalidArgumentException;

class FetchNewBlogPostsAction
{
    public function execute()
    {
        $blogTag = config('bear-blogger.tag');

        if (empty($blogTag)) {
            throw new InvalidArgumentException("Add BEAR_BLOGGER_TAG to .env!");
        }

        $tag = BearTag::whereTitle($blogTag)->first();

        $inserts = 0;

        foreach ($tag->notes as $bearNote) {
            if ($this->isWip($bearNote)) {
                continue;
            }

            $post = BlogPost::withoutGlobalScope('published')->whereBearId($bearNote->id)->first();

            if ($post) {
                continue;
            }

            $inserts++;

            $post = $this->insertPost($bearNote);

            (new LinkBlogPostCategoriesAction)->execute($bearNote, $post);
        }

        return $inserts;
    }

    public static function cleanContent($content)
    {
        $content = Str::after($content, '---');
        $content = str_replace(['“', '”'], '"', $content);

        return str_replace('’', "'", $content);
    }

    protected function isWip($bearNote): bool
    {
        return $bearNote->tags()->where('title', 'wip')->exists();
    }

    protected function insertPost($bearNote): BlogPost
    {
        return BlogPost::create([
                'bear_id' => $bearNote->id,
                'checksum' => $bearNote->checksum,
                'slug' => Str::slug($bearNote->title),
                'title' => $bearNote->title,
                'content' => $this->cleanContent($bearNote->content),
                'publish_at' => now(),
            ]);
    }
}
