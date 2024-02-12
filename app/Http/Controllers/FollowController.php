<?php

namespace App\Http\Controllers;

use App\Models\Follow;
use App\Models\User;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function createFollow(User $user){
        if($user->id==auth()->user()->id){
            return back()->with('failure','You cannot follow yourself');
        }
        $existCheck = Follow::where([['user_id','=',auth()->user()->id],['followedUser',"=",$user->id]])->count();
        if($existCheck){
            return back()->with('failure','You are already following that user');
        }
        //if we use two [[first condition],[second condition]] it mean first condition and second condition
        $newFollow = new Follow;
        $newFollow->user_id =auth()->user()->id;
        $newFollow->followedUser = $user->id;
        $newFollow->save();
        return back()->with('success','User followed successfully');
    }
    public function removeFollow(User $user){
        Follow::where([['user_id',"=",auth()->user()->id],['followedUser',"=",$user->id]])->delete();
        return bacK()->with('success',"You unfollowed this user");
    }
}
