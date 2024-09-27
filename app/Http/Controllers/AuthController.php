<?php

namespace App\Http\Controllers;

use App\Mail\PasswordResetMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{

    /**
     * Function - Login page view
     * 
     * @return View
     */
    public function login(){
        return view('auth.login');
    }

    /**
     * Function - Forgot password page view
     * 
     * @return View
     */
    public function forgot_password(){
        return view('auth.forgot');
    }

    /**
     * Function - Set password page view
     * 
     * @param Request $request
     * 
     * @return View
     */
    public function set_password(Request $request){
        $token = $request->token;
        if(!$token){
            return response('', 404);
        }
        $data = json_decode(Crypt::decryptString($token), true);
        if(!$data || !isset($data['user']) || !isset($data['created_at']) || ($data['created_at'] + 5*60) < time()){
            return redirect()->route('login')->with('error', 'Page expired! Please login to continue!');
        }
        $user = User::where('id', $data['user'])->where('status', '>', 0)->first();
        if(!$user){
            return response('', 404);
        }
        $data['created_at'] = time();
        $encoded_str = Crypt::encryptString(json_encode($data));
        return view('auth.password', ['token' => $encoded_str]);
    }

    /**
     * Function - Set password action
     * 
     * @param Request $request
     * 
     * @return Redirect
     */
    public function do_set_password(Request $request){
        $request->validate([
            'verify_token' => 'required',
            'password' => 'required|min:8|max:25',
            'confirm_password' => 'required|same:password'
        ]);
        $data = json_decode(Crypt::decryptString($request->verify_token), true);
        if(!$data || !isset($data['user']) || !isset($data['created_at']) || ($data['created_at'] + 5*60) < time()){
            return redirect()->route('login')->with('error', 'Page expired! Please login to continue!');
        }
        $user = User::where('id', $data['user'])->where('status', '>', 0)->first();
        if(!$user){
            return response('', 404);
        }
        $user->password = bcrypt($request->password);
        $result = $user->save();
        if (!$result) {
            return redirect()->back()->with('error', 'Unable to save password. Please try again!');
        }
        return redirect()->route('login')->with('success', 'Password chanegd successfully!');
    }

    /**
     * Function - Forgot password action
     * 
     * @param Request $request
     * 
     * @return Redirect
     */
    public function do_forgot_password(Request $request){
        $username = $request->username;
        if(!$username){
            return redirect()->back()->with('success', 'Please check your email for instructions.');
        }
        $user = User::where(filter_var($username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username', $username)->where('status', '>', 0)->first();
        if(!$user){
            return redirect()->back()->with('success', 'Please check your email for instructions.');
        }
        $data = [
            'user' => $user->id,
            'created_at' => time()
        ];
        $encoded_str = Crypt::encryptString(json_encode($data));
        Mail::to($user->email)->send(new PasswordResetMail($user->name ? $user->name : $user->username, route('forgot_password.do_verify', ['token' => $encoded_str])));
        return redirect()->back()->with('success', 'Please check your email for instructions.');
    }

    
    /**
     * Function - Login action
     * 
     * @param Request $request
     * 
     * @return Redirect
     */
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

    /**
     * Function - Logout action
     * 
     * @return Redirect
     */
    public function do_logout(){
        Auth::logout();
        return redirect()->route('login');
    }

     /**
     * Function - Generate a encrypted token
     * 
     * @return Redirect
     */
    public function generate_token(Request $request){
        $user = Auth::user();
        $encoded_str = null;
        if(!$request->remove){
            $data = [
                'user' => $user->id,
                'created_at' => time()
            ];
            $encoded_str = Crypt::encryptString(json_encode($data));
        }        
        $user->public_token = $encoded_str;
        $result = $user->save();
        if(!$result){
            return redirect()->back()->with('error', !$request->remove ? 'Unable to create new public token!' : 'Unable to remove public token!');
        }
        return redirect()->back()->with('success', !$request->remove ? 'New public token created successfully!' : 'Public token remoed successfully!');
    }
}
