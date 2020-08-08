<?php

namespace AzurInspire\BearBlogger\Actions;

use AzurInspire\BearBlogger\Models\BlogPost;
use Illuminate\Support\Facades\DB;

class PublishTopicsAction
{
    public function execute(BlogPost $post)
    {
        $inserts = 0;

        $post->topics->each(function ($topic) use ($post, &$inserts) {
            if (DB::connection('production')->table('topics')->where('id', $topic->id)->doesntExist()) {
                $remoteTopicId = DB::connection('production')->table('topics')->insertGetId([
                    'id' => $topic->id,
                    'slug' => $topic->slug,
                    'name' => $topic->name,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                DB::connection('production')->table('post_topic')->insert([
                    'blog_post_id' => $post->id,
                    'blog_topic_id' => $remoteTopicId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $inserts++;
            }
        });

        return $inserts;
    }
}
