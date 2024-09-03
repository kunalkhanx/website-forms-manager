<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{

    /**
     * Function - Profile update page
     * 
     * @return View
     */
    public function index(){
        $user = Auth::user();
        return view('profile', ['user' => $user]);
    }

    /**
     * Function - Update profile action
     * 
     * @param Request $request
     * 
     * @return Redirect
     */
    public function do_update(Request $request){
        $user = Auth::user();
        $request->validate([
            'name' => 'required|min:3|max:160',
            'username' => 'required|min:4|max:25|unique:users,username,' . $user->id . ',id',
            'email' => 'required|email|min:4|max:50|unique:users,email,' . $user->id . ',id',
        ]);

        if ($request->password) {
            $request->validate([
                'password' => 'required|min:8|max:25',
                'confirm_password' => 'required|same:password',
            ]);
        }
        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        if ($request->password) {
            $user->password = bcrypt($request->password);
        }        
        $result = $user->save();
        if (!$result) {
            return redirect()->back()->with('error', 'Unable to update profile. Please try again!')->withInput();
        }
        return redirect()->back()->with('success', 'Profile updated successfully!');
    }
}
