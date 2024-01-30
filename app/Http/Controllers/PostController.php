<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function viewSinglePost(Post $post)
    {
        $post['body']= strip_tags(Str::markdown($post->body),'<p><ul><li><ol>');
        return view('single-post', ['post' => $post]);
    }
    public function storeNewPost(Request $request)
    {
        $incomingFields = $request->validate([
            'title' => "required",
            'body' => "required"
        ]);
        $incomingFields['title'] = strip_tags($incomingFields['title']);
        $incomingFields['body'] = strip_tags($incomingFields['body']);
        $incomingFields['user_id'] = auth()->id();
        $newPost = Post::create($incomingFields);
        return redirect("/post/{$newPost->id}")->with('success', 'new Post successfully created');
    }
    public function showCreateForm()
    {
        return view('create-post');
    }
}
