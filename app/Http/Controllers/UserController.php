<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Psy\CodeCleaner\PassableByReferencePass;

class UserController extends Controller
{
    public function showCorrectHomePage()
    {
        if (auth()->check()) {
            return view('homepage-feed');

        } else {
            return view('homepage');
        }
    }
    public function login(Request $request)
    {
        $incomingFields = $request->validate([
            "loginusername" => "required",
            'loginpassword' => "required"
        ]);
        if (auth()->attempt(['username' => $incomingFields['loginusername'], 'password' => $incomingFields['loginpassword']])) {
            $request->session()->regenerate();
            return 'congrats';
        } else {
            return 'fails';
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
        User::create($incomingFields);
        return "Hello from register functon";
    }

}
