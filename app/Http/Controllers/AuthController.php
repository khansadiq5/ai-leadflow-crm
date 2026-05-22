<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showRegister(){
        return view('auth.register');
    }

    public function register(Request $req){
        $req->validate([
            'name'=>'required|string|max:100',
            'email'=>'required|email|unique:users,email',
            'password'=>'required|min:6|confirmed'
        ]);

        $user = User::create([
            'name'=> $req->name,
            'email'=> $req->email,
            'password'=> Hash::make($req->password),
            'role'=> 'sales_executive',
            'status'=> 'active',
        ]);

        return redirect()->route('login');
    }

    public function showLogin(){
        return view('auth.login');
    }

    public function login(Request $req){
        $credentials = $req->validate([
            'email'=> 'required|email',
            'password'=>'required'
        ]);

        if(Auth::attempt($credentials)){
            $req->session()->regenerate();
            return redirect()->route('dashboard');
        }
        
        return back()->withErrors([
            'email'=> 'Email and Password does not match',
        ])->onlyInput('email');
    }

    public function logout(Request $req){

        Auth::logout();

        $req->session()->invalidate();
        $req->session()->regenerateToken();

        return redirect()->route('login');
    }

}
