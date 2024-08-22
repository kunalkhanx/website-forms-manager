<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(10);
        return view('users.index', ['users' => $users]);
    }


    public function create()
    {
        return view('users.form', ['user' => new User]);
    }

    public function update(User $user)
    {
        if(!$user){
            return response('', 404);
        }
        return view('users.form', ['user' => $user]);
    }

    public function do_create(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3|max:160',
            'username' => 'required|min:4|max:25|unique:users,username',
            'email' => 'required|email|min:4|max:50|unique:users,email',
            'password' => 'required|min:8|max:25',
            'confirm_password' => 'required|same:password',
            'login_block' => 'nullable',
            'is_super' => 'nullable'
        ]);

        $user = new User;
        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        if ($request->login_block) {
            $user->status = 0;
        }
        if ($request->is_super) {
            $user->is_super = 1;
        }
        $result = $user->save();
        if (!$result) {
            return redirect()->back()->with('error', 'Unable to create user. Please try again!')->withInput();
        }
        return redirect()->back()->with('success', 'User created successfully!');
    }

    public function do_update(Request $request, User $user)
    {
        if(!$user){
            return response('', 404);
        }
        $request->validate([
            'name' => 'required|min:3|max:160',
            'username' => 'required|min:4|max:25|unique:users,username,' . $user->id . ',id',
            'email' => 'required|email|min:4|max:50|unique:users,email,' . $user->id . ',id',
            'login_block' => 'nullable',
            'is_super' => 'nullable'
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
        if ($request->login_block) {
            $user->status = 0;
        }else{
            $user->status = 1;
        }
        if ($request->is_super) {
            $user->is_super = 1;
        }
        $result = $user->save();
        if (!$result) {
            return redirect()->back()->with('error', 'Unable to update user. Please try again!')->withInput();
        }
        return redirect()->back()->with('success', 'User updated successfully!');

    }


    public function do_delete(User $user){
        if(!$user){
            return response('', 404);
        }
        $result = $user->delete();
        if(!$result){
            return redirect()->back()->with('error', 'Unable to delete the user!');
        }
        return redirect()->back()->with('success', 'User deleted successfully!');
    }
}
