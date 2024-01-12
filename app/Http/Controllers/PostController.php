<?php

namespace App\Http\Controllers;

use App\Jobs\SendPostNotification;
use App\Models\Post;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\PostNotification;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function store(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'website_id' => 'required|exists:websites,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Create a new post
        $post = Post::create($validator->validated());

        // Notify subscribers
        $subscribers = Subscription::where('website_id', $request->input('website_id'))->get();

        foreach ($subscribers as $subscriber) {
            Mail::to($subscriber->email)->send(new PostNotification($post));
        }
		
		// Dispatch the job to send notifications asynchronously
        SendPostNotification::dispatch($post)->onQueue('notifications');

        return response()->json(['message' => 'Post created successfully!', 'data' => $post], 201);
    }
}
