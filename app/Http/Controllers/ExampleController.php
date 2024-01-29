<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExampleController extends Controller
{
    public function homePage()
    {
        $myName = 'leang';
        $friends = ['Jack', 'John', 'Jane'];
        return view('homepage', ['name' => $myName, 'friends' => $friends]);
    }
    public function aboutPage()
    {
        return view('single-post');
    }
}
