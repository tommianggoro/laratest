<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Hash;
use Session;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;

class AuthController extends Controller
{
    //
    public function index(){
    	if(Auth::check()){
    		return redirect()->route('home');
    	}
    	return view('auth.login');
    }

    public function login(Request $request){

    	$rules = [
    		'email' => 'required|string|email',
    		'password' => 'required|string'
    	];

    	$messages = [
    		'email.required' => 'Email wajib',
    		'email.email' => 'Email tak valid',
    		'password.required' => 'password wajib'
    	];

    	$validator = Validator::make($request->all(), $rules, $messages);

    	if($validator->fails()){
    		return redirect()->back()->withErrors($validator)->withInput($request->all());
    	}

    	$data = [
    		'email' => $request->input('email'),
    		'password' => $request->input('password')
    	];

    	Auth::attempt($data);

    	if(Auth::check()){
    		return redirect()->route('home');
    	}

    	Session::flash('error', 'Email / Password salah');
    	return redirect()->route('login');

    }

    public function showFormregister(){
    	return view('register');
    }

    public function register(Request $request){
    	$rules = [
    		'name' => 'required|string',
    		'password' => 'required|confirmed',
    		'email' => 'required|email',
    	];

    	$messages = [
            'name.required'         => 'Nama Lengkap wajib diisi',
            'name.min'              => 'Nama lengkap minimal 3 karakter',
            'name.max'              => 'Nama lengkap maksimal 35 karakter',
            'email.required'        => 'Email wajib diisi',
            'email.email'           => 'Email tidak valid',
            'email.unique'          => 'Email sudah terdaftar',
            'password.required'     => 'Password wajib diisi',
            'password.confirmed'    => 'Password tidak sama dengan konfirmasi password'
        ];

    	$validator = Validator::make($request->all(), $rules, $messages);

    	if($validator->fails()){
    		return redirect()->back()->withErrors($validator)->withInput($request->all);
    	}

    	$user = new User;
    	$user->name = $request->name;
    	$user->email = $request->email;
    	$user->password = Hash::make($request->password);
    	$user->email_verified_at = \Carbon\Carbon::now();

    	$saveUser = $user->save();

    	if($saveUser){
    		Session::flash('success', 'Berhasil');
    		return redirect()->route('home');
    	}

    	Session::flash('errors', ['' => 'Gagal']);
    	return redirect()->route('register');
    }

    public function logout(){
    	Auth::logout();
    	return redirect()->route('login');
    }
}
