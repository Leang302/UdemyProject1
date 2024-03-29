<?php

namespace App\Http\Controllers;


use App\Jobs\SendNewPostEmail;
use App\Mail\NewPostEmail;
use App\Models\Post;
use Illuminate\Auth\Access\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function actuallyUpdate(Post $post,Request $request){
        $incomingFields = $request->validate([
            "title"=>'required',
            "body"=>'required'
        ]);
        $incomingFields['title']=strip_tags($incomingFields['title']);
        $incomingFields['body']=strip_tags($incomingFields['body']);
        $post->update($incomingFields);
        return redirect('post/'.$post->id)->with("success","The post has been updated");
    }
    public function showEditForm(Post $post){
        return view('edit-post',['post'=>$post]);
    }
    public function deleteApi(Post $post)
    {
        $post->delete();
       return 'post successfully delete';

    }
    public function delete(Post $post,)
    {
        $post->delete();
        return redirect('/profile/' . auth()->user()->username)->with('success', 'Post succesfully deleted');

    }
    public function viewSinglePost(Post $post)
    {
        $post['body'] = strip_tags(Str::markdown($post->body), '<p><ul><li><ol>');
        return view('single-post', ['post' => $post]);
    }
    public function storeNewPostApi(Request $request){
        $incomingFields = $request->validate([
            'title' => "required",
            'body' => "required"
        ]);
        $incomingFields['title'] = strip_tags($incomingFields['title']);
        $incomingFields['body'] = strip_tags($incomingFields['body']);
        $incomingFields['user_id'] = auth()->id();
        $newPost = Post::create($incomingFields);
        dispatch(new SendNewPostEmail(['sendTo'=>auth()->user()->email,'name'=>auth()->user()->username,
        'title'=>$newPost->title]));
        return $newPost->id;
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
        dispatch(new SendNewPostEmail(['sendTo'=>auth()->user()->email,'name'=>auth()->user()->username,
        'title'=>$newPost->title]));
        return redirect("/post/{$newPost->id}")->with('success', 'new Post successfully created');
    }
    public function showCreateForm()
    {
        return view('create-post');
    }
    public function search($term){
       $posts = Post::search($term)->get();
       $posts->load('user:id,username,avatar');
       return $posts;
    }
}
