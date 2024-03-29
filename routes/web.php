<?php

use App\Events\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FollowController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('admins-only',function(){return 'admin page';})->middleware("can:visitAdminPage");
//user related routes
Route::get('/', [UserController::class, 'showCorrectHomePage'])->name('login');
Route::post('/register', [UserController::class, 'register'])->middleware('guest');
Route::post('/login', [UserController::class, 'login'])->middleware('guest');
Route::post('/log-out', [UserController::class, 'logout'])->middleware('mustBeLoggedIn');
Route::get('/manage-avatar',[UserController::class,'showAvatarForm'])->middleware('mustBeLoggedIn');
Route::post('/manage-avatar',[UserController::class,'uploadAvatar'])->middleware('mustBeLoggedIn');

//follow related route
Route::post('/create-follow/{user:username}',[FollowController::class,'createFollow'])->middleware('mustBeLoggedIn');
Route::post('/remove-follow/{user:username}',[FollowController::class,'removeFollow'])->middleware('mustBeLoggedIn');

//post related routes
Route::get('/create-post', [PostController::class, 'showCreateForm'])->middleware('mustBeLoggedIn');
Route::post('/create-post', [PostController::class, 'storeNewPost'])->middleware('mustBeLoggedIn');
Route::get('/post/{post}', [PostController::class, 'viewSinglePost']);
Route::delete('/post/{post}', [PostController::class, 'delete'])->middleware('can:delete,post');
Route::get('/post/{post}/edit',[PostController::class,'showEditForm'])->middleware('can:update,post');
Route::put('/post/{post}/edit',[PostController::class,'actuallyUpdate'])->middleware('can:update,post');
Route::get('/search/{term}',[PostController::class,'search']);

//profile
Route::get('/profile/{user:username}',[UserController::class,'showProfile']); 
Route::get('/profile/{user:username}/followers',[UserController::class,'profileFollowers']); 
Route::get('/profile/{user:username}/following',[UserController::class,'profileFollowing']); 

Route::middleware('cache.headers:public;max_age=20;etag')->group(function(){
    Route::get('/profile/{user:username}/raw',[UserController::class,'showProfileRaw']); 
    Route::get('/profile/{user:username}/followers/raw',[UserController::class,'profileFollowersRaw']); 
    Route::get('/profile/{user:username}/following/raw',[UserController::class,'profileFollowingRaw']); 
}); 
//chat related routes
Route::post('/send-chat-message',function (Request $request){
    $formFields = $request->validate([
        'textvalue'=>'required'
    ]);
    //!trim(parameter) = to check if it's empty or contain only whitespace
    if(!trim(strip_tags($formFields['textvalue']))){
        return response()->noContent();
    }
    //broadcast = brocast to all user
    broadcast(new ChatMessage(['username'=>auth()->user()->username,'textvalue'=>strip_tags($request['textvalue']),'avatar'=>auth()->user()->avatar]))->toOthers();
    return response()->noContent();

})->middleware('mustBeLoggedIn');