<?php

namespace App\Console\Commands;

use App\Models\Post;
use App\Models\Subscription;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\PostNotification;

class SendPostNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:post-notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email notifications to subscribers about new posts';

    /**
     * Execute the console command.
     *
     * @return int
     */
 
    public function handle()
    {
        $posts = Post::where('created_at', '>', now()->subDay())->get();

        foreach ($newPosts as $post) {
        $subscribers = Subscription::where('website_id', $post->website_id)->get();

        foreach ($subscribers as $subscriber) {
            // Check if this post has already been sent to this subscriber
            if (!SentPost::where('post_id', $post->id)->where('subscriber_id', $subscriber->id)->exists()) {
                // Send notification
                Mail::to($subscriber->email)->send(new PostNotification($post));

                // Record that this post has been sent to this subscriber
                SentPost::create([
                    'post_id' => $post->id,
                    'subscriber_id' => $subscriber->id,
                ]); 
            }
        }
    }
        $this->info('Post notifications sent successfully.');
    }
}
