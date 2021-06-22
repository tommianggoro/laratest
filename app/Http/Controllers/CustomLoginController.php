<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Hash;
use Session;
// use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CustomLoginController extends Controller
{
    //
    public function index()
    {
        return view('auth.login');
    }  
}
