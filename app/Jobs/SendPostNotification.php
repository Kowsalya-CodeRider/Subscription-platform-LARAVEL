<?php

namespace App\Jobs;

use App\Mail\PostNotification;
use App\Models\Post;
use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

class SendPostNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
	
	protected $post;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Check if this post has already been processed
        $postKey = 'processed_post_' . $this->post->id;

        if (Cache::has($postKey)) {
            // This post has already been processed, skip sending notifications
            return;
        }

        $subscribers = Subscription::where('website_id', $this->post->website_id)->get();

        foreach ($subscribers as $subscriber) {
            Mail::to($subscriber->email)->send(new PostNotification($this->post));
        }

        // Mark this post as processed to avoid duplicate notifications
        Cache::put($postKey, true, now()->addDays(1));
    }
}
