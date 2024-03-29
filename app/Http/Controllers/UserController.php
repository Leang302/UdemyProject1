<?php

namespace App\Http\Controllers;

use App\Events\ExampleEvent;
use App\Models\Follow;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;
use PhpParser\Node\Stmt\Catch_;

class UserController extends Controller{   
    //this is the way to share data across function 
    private function getSharedData($user){
        $currentlyfollowing=0;
        if(auth()->check()){
            $currentlyfollowing= Follow::where([['user_id',"=",auth()->user()->id],['followedUser','=',$user->id]])->count();
        }
        View::share('sharedData',['currentlyFollowing'=>$currentlyfollowing,'userImage'=>$user->avatar,'username'=>$user->username,'postCounts'=>$user->posts()->get()->count(),'userId'=>$user->id,"followersCount"=>$user->followers()->get()->count(),"followingCount"=>$user->following()->get()->count()]);
    }
    public function showProfile(User $user){
        $this->getSharedData($user);
        return view("/profile-posts",['posts'=>$user->posts()->latest()->get()]);
    }
    public function showProfileRaw(User $user){
        return response()->json(['theHTML'=>view('profile-posts-only',['posts'=>$user->posts()->latest()->get()])->render(),'docTitle'=>$user->username."'s profile"]);
    }
    public function profileFollowers(User $user){
        $this->getSharedData($user);
        return view("/profile-followers",['followers'=>$user->followers()->latest()->get()]);
    }
    public function profileFollowersRaw(User $user){
        return response()->json(['theHTMl'=>view('profile-followers-only',['followers'=>$user->followers()->latest()->get()])->render(),'docTitle'=>$user->username,"'s followers"]);
    }
    public function profileFollowing(User $user){
        $this->getSharedData($user);
        return view("/profile-following",['followings'=>$user->following()->latest()->get()]);
    }
    public function profileFollowingRaw(User $user){
        return response()->json(['theHTML'=>view('profile-following-only',['followings'=>$user->following()->latest()->get()])->render(),'docTitle'=>$user->username."'s following"]);
    }
    public function uploadAvatar(Request $request){
        //resizing image via = intervention
        $request->validate([
            'avatar'=>'required|image|max:6000'
        ]);
        $user= auth()->user();
        $fileName = $user->id.'-'. uniqid().'.jpg';
        
        $imageData = Image::make($request->file('avatar'))->fit(120)->encode('jpg');
        Storage::put('/public/avatars/'.$fileName,$imageData);

        $oldAvatar = $user->avatar;

        $user->avatar= $fileName;
        $user->save();
        if($oldAvatar!='/fallback-avatar.jpg'){
            Storage::delete(str_replace('/storage','/public',$oldAvatar));
        }
        return redirect('/profile/'.auth()->user()->username)->with('success','You have successfully updated your profile');

    }
    public function showAvatarForm(){
        return view('avatar-form');
    }
   
    public function logout()
    {
        event(new ExampleEvent(['username'=>auth()->user()->username,'action'=>'log out']));
        auth()->logout();
        return redirect('/')->with("success", "You are now logged out");
    }
    public function showCorrectHomePage()
    {
        if (auth()->check()) {
            return view('homepage-feed',['feedposts'=>auth()->user->feedPosts()->latest()->paginate(4)]);

        } else {
            $postCount = Cache::remember('postCount',20,function(){
                //sleep(5) to test cache
                return Post::count();
            });
            return view('homepage',['postCount'=>$postCount]);
        }
    }
    public function loginApi(Request $request){
        $incomingFields=$request->validate([
            'username'=>'required',
            'password'=>'required'
        ]);
        if(auth()->attempt($incomingFields)){
            $user = User::where('username',$incomingFields['username'])->first();
            $token = $user->createToken('ourapptoken')->plainTextToken;
            return $token;
        }
        return 'invalid user'; 
    }   
    public function login(Request $request)
    {
        $incomingFields = $request->validate([
            "loginusername" => "required",
            'loginpassword' => "required"
        ]);
        if (auth()->attempt(['username' => $incomingFields['loginusername'], 'password' => $incomingFields['loginpassword']])) {
            $request->session()->regenerate();
            event(new ExampleEvent(['username'=>auth()->user()->username,'action'=>'log in']));
            return redirect('/')->with("success", "You have successfully login");
        } else {
            return redirect('/')->with('failure', 'Invalid log in');
        }
    }
    public function register(Request $request)
    {
        $incomingFields = $request->validate([
            "username" => ['required', 'min:3', 'max:20', Rule::unique('users', 'username')],
            "email" => ['required', 'email', Rule::unique("users", 'email')],
            "password" => ["required", 'min:3', 'max:30', 'confirmed'],
        ]);
        $incomingFields['password'] = bcrypt($incomingFields['password']);
        $user = User::create($incomingFields);
        auth()->login($user);
        return redirect('/')->with('success', 'You are now logged in');
    }

}
