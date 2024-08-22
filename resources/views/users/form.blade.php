@extends('app')

@section('main')
    <div class="container mx-auto max-w-screen-xl p-4">
        <div class="mb-6 flex items-center">
            <a href="{{route('users')}}" class="p-4 pl-0">
                <svg class="w-8 h-8" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12l4-4m-4 4 4 4"/>
                </svg>                  
            </a>
            <h2 class="text-3xl">{{$user->id ? 'Update' : 'Create'}} User</h2>
        </div>
        <form action="{{$user->id ? route('users.do_update', ['user' => $user->id]) : route('users.do_create')}}" method="POST">
            @csrf
            @if($user->id) @method('PATCH') @endif
            <div class="grid grid-cols-2 gap-4">
                <div class="form-control">
                    <label for="name">Name</label>
                    <input type="text" id="name" placeholder="Enter user's full name" name="name" value="{{old('name', $user->name)}}">
                    @error('name')
                        <p class="err">{{$message}}</p>
                    @enderror
                </div>
                <div class="form-control">
                    <label for="username">Username</label>
                    <input type="text" id="username" placeholder="Enter unique username" name="username" value="{{old('username', $user->username)}}">
                    @error('username')
                        <p class="err">{{$message}}</p>
                    @enderror
                </div>
                <div class="form-control">
                    <label for="email">Email</label>
                    <input type="email" id="email" placeholder="Enter users email address" name="email" value="{{old('email', $user->email)}}">
                    @error('email')
                        <p class="err">{{$message}}</p>
                    @enderror
                </div>
                <div></div>
                <div class="form-control">
                    <label for="password">Password</label>
                    <input type="password" id="password" placeholder="Enter secret password" name="password">
                    @error('password')
                        <p class="err">{{$message}}</p>
                    @enderror
                </div>
                <div class="form-control">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" placeholder="Type the password again" name="confirm_password">
                    @error('confirm_password')
                        <p class="err">{{$message}}</p>
                    @enderror
                </div>
                <div class="flex items-start mb-5">
                    <div class="flex items-center h-5">
                        <input id="is_super" name="is_super" type="checkbox" value="1" {{old('is_super', $user->is_super) ? 'checked' : ''}}
                            class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800" />
                    </div>
                    <label for="is_super" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Super Admin</label>
                </div>
                <div class="flex items-start mb-5">
                    <div class="flex items-center h-5">
                        <input id="login_block" name="login_block" type="checkbox" value="1" {{old('login_block', ($user->status == 0 ? '1' : null)) ? 'checked' : ''}}
                            class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800" />
                    </div>
                    <label for="login_block" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Block Login</label>
                </div>
                <div>
                    <button type="submit"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">{{$user->id ? 'Update' : 'Create'}}</button>
                </div>
            </div>
        </form>
    </div>
@endsection
