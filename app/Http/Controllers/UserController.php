<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /*
     * Users
     * */
    public function index()
    {
        $users = User::all();
        return view('user',compact('users'));
    }
}
