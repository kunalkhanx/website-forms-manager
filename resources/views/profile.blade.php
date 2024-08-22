@extends('app')

@section('main')
    <div class="container mx-auto max-w-screen-xl p-4 flex flex-col gap-6">
        <h2 class="text-3xl">Update Account</h2>
        <form action="{{route('profile.do_update')}}" method="POST">
            @csrf
            @method('PATCH')
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="form-control">
                    <label for="name">Name</label>
                    <input type="text" id="name" placeholder="Enter user's full name" name="name" value="{{$user->name}}">
                    @error('name')
                        <p class="err">{{$message}}</p>
                    @enderror
                </div>
                <div class="form-control">
                    <label for="username">Username</label>
                    <input type="text" id="username" placeholder="Enter unique username" name="username" value="{{$user->username}}">
                    @error('username')
                        <p class="err">{{$message}}</p>
                    @enderror
                </div>
                <div class="form-control">
                    <label for="email">Email</label>
                    <input type="email" id="email" placeholder="Enter users email address" name="email" value="{{$user->email}}">
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
               
                
                <div>
                    <button type="submit"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Save</button>
                </div>
            </div>
        </form>

        <hr>

        <form id="generate_token_form" action="{{route('generate_token')}}" method="POST" class="flex flex-col gap-6">
            @csrf
            <div class="form-control">
                <label for="">Public Token</label>
                <textarea rows="3" placeholder="Please generate new token">{{$user->public_token}}</textarea>
            </div>
            <div>
                <div>
                    <button type="submit"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Generate</button>
                </div>
            </div>
        </form>

        <script>
             document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('generate_token_form');

        form.addEventListener('submit', function(event) {
            // Display a confirmation dialog
            const confirmed = confirm('Your previous token will be expired. Are you sure to procced?');

            // If the user clicks "Cancel", prevent form submission
            if (!confirmed) {
                event.preventDefault();
            }
        });
    });
        </script>
    </div>
@endsection
