<?php

namespace AzurInspire\BearBlogger\Actions;

use AzurInspire\BearBlogger\Models\BlogPost;
use AzurInspire\BearBlogger\Models\BlogTopic;
use BearSync\BearNote;
use Illuminate\Support\Str;
use InvalidArgumentException;

class LinkBlogPostCategoriesAction
{
    public function execute(BearNote $bearNote, BlogPost $post): void
    {
        $blogTag = config('bear-blogger.tag');

        if (empty($blogTag)) {
            throw new InvalidArgumentException("Add BEAR_BLOGGER_TAG to .env!");
        }

        $topicTags = $bearNote->tags()->where('title', 'like', $blogTag . '/%')->get();
        $topics = collect();

        foreach ($topicTags as $tag) {
            $name = Str::after($tag->title, $blogTag . '/');
            $topic = BlogTopic::whereName($name)->first();

            if ($topic) {
                $topics->push($topic->id);

                continue;
            }

            $topic = BlogTopic::create([
                    'slug' => Str::slug($name),
                    'name' => $name,
                ]);

            $topics->push($topic->id);
        }

        if ($topics->isNotEmpty()) {
            $post->topics()->sync($topics);
        }
    }
}
