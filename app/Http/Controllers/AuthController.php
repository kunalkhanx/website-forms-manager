<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class AuthController extends Controller
{
    public function login(){
        return view('login');
    }

    public function do_login(Request $request){
        if(!$request->username || !$request->password){
            return redirect()->back()->with('error', 'Please enter your valid username & password');
        }
        $user = User::where('username', $request->username)->where('status', '>', 0)->first();
        if(!$user){
            return redirect()->back()->with('error', 'Please enter your valid username & password');
        }
        if(!Auth::attempt([
            'email' => $user->email,
            'password' => $request->password
        ], true)){
            return redirect()->back()->with('error', 'Please enter your valid username & password');
        }
        return redirect()->route('dashboard')->with('success', 'Login success!');
    }

    public function do_logout(){
        Auth::logout();
        return redirect()->route('login');
    }

    public function generate_token(){
        $user = Auth::user();
        $data = [
            'user' => $user->id,
            'created_at' => time()
        ];
        $encoded_str = Crypt::encryptString(json_encode($data));
        $user->public_token = $encoded_str;
        $result = $user->save();
        if(!$result){
            return redirect()->back()->with('error', 'Unable to create new public token!');
        }
        return redirect()->back()->with('success', 'New public token created successfully!');
    }
}
