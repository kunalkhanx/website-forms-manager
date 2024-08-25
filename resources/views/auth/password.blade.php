@extends('auth')

@section('title', 'Password Reset')

@section('main')
    <div class="w-full max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
        <form action="{{ route('forgot_password.do_set_password') }}" class="flex flex-col gap-4" method="POST">
            @csrf
            <input type="hidden" name="verify_token" value="{{$token}}">
            <h2 class="text-3xl">Password Reset</h2>

            <div class="form-control">
                <label for="password">Create password</label>
                <input type="password" id="password" name="password" placeholder="Enter new password" />
                @error('password')
                    <p class="err">{{$message}}</p>
                @enderror
            </div>

            <div class="form-control">
                <label for="confirm_password">Confirm password</label>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Type password again" />
                @error('confirm_password')
                    <p class="err">{{$message}}</p>
                @enderror
            </div>

            <div class="flex justify-between items-center max-sm:flex-col gap-4">
                <button type="submit"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Save</button>
                <a href="{{route('login')}}" class="hover:text-blue-700">Back to login</a>
            </div>

        </form>

    </div>
@endsection
