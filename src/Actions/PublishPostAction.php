<?php

namespace AzurInspire\BearBlogger\Actions;

use AzurInspire\BearBlogger\Models\BlogPost;
use Illuminate\Support\Facades\DB;

class PublishPostAction
{
    public function execute(BlogPost $blogPost)
    {
        $remotePost = DB::connection('production')->table('blog_posts')->where('id', $blogPost->id)->first();
        $content = DB::table('blog_posts')->select('content')->where('id', $blogPost->id)->first();

        if (! $remotePost) {
            DB::connection('production')->table('blog_posts')->insert([
                'id' => $blogPost->id,
                'bear_id' => $blogPost->bear_id,
                'slug' => $blogPost->slug,
                'title' => $blogPost->title,
                'content' => $content->content,
                'checksum' => $blogPost->checksum,
                'created_at' => now(),
                'updated_at' => now(),
                'publish_at' => now(),
                'is_published' => true,
            ]);

            $topicInsert = (new PublishTopicsAction)->execute($blogPost);
            $imagesInsert = (new PublishImagesAction)->execute($blogPost);

            $blogPost->is_published = true;
            $blogPost->save();

            return ['published' => true, 'topics' => $topicInsert, 'images' => $imagesInsert];
        }

        $topicInsert = (new PublishTopicsAction)->execute($blogPost);
        $imagesInsert = (new PublishImagesAction)->execute($blogPost);


        if ((string) $blogPost->checksum === (string) $remotePost->checksum) {
            $blogPost->is_published = true;
            $blogPost->save();

            return ['published' => false, 'topics' => $topicInsert, 'images' => $imagesInsert];
        }

        DB::connection('production')->table('blog_posts')->where('id', $blogPost->id)->update([
            'title' => $blogPost->title,
            'content' => $content->content,
            'checksum' => $blogPost->checksum,
            'updated_at' => now(),
            'is_published' => true,
        ]);

        $blogPost->is_published = true;
        $blogPost->save();

        return ['published' => true, 'topics' => $topicInsert, 'images' => $imagesInsert];
    }
}
