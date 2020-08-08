<?php

namespace AzurInspire\BearBlogger\Actions;

use AzurInspire\BearBlogger\Models\BlogPost;
use Illuminate\Support\Facades\DB;

class PublishPostAction
{
    public function execute(BlogPost $post)
    {
        $remotePost = DB::connection('production')->table('posts')->where('bear_id', $post->bear_id)->first();

        if (! $remotePost) {
            DB::connection('production')->table('posts')->insert([
                'bear_id' => $post->bear_id,
                'slug' => $post->slug,
                'title' => $post->title,
                'content' => $post->content,
                'checksum' => $post->checksum,
                'created_at' => now(),
                'updated_at' => now(),
                'publish_at' => now(),
                'is_published' => true,
            ]);

            $topicInsert = (new PublishTopicsAction)->execute($post);

            $post->is_published = true;
            $post->save();

            return ['published' => true, 'topics' => $topicInsert];
        }

        $topicInsert = (new PublishTopicsAction)->execute($post);

        if ((string) $post->checksum === (string) $remotePost->checksum) {
            $post->is_published = true;
            $post->save();

            return ['published' => false, 'topics' => $topicInsert];
        }

        DB::connection('production')->table('posts')->where('bear_id', $post->bear_id)->update([
            'title' => $post->title,
            'content' => $post->content,
            'checksum' => $post->checksum,
            'updated_at' => now(),
            'is_published' => true,
        ]);

        $post->is_published = true;
        $post->save();

        return ['published' => true, 'topics' => $topicInsert];
    }
}
