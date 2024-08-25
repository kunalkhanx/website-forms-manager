@extends('auth')

@section('title', 'Login')

@section('main')
    <div class="w-full max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
        <form action="{{ route('do_login') }}" class="flex flex-col gap-4" method="POST">
            @csrf
            <h2 class="text-3xl">Login</h2>

            <div class="form-control">
                <input type="text" id="username" name="username" placeholder="Username" autocomplete="off" />
            </div>

            <div class="form-control">
                <input type="password" id="password" name="password" placeholder="Password" />
            </div>

            <div class="flex justify-between items-center max-sm:flex-col gap-4">
                <button type="submit"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Login</button>
                <a href="{{route('forgot_password')}}" class="hover:text-blue-700">Forgot password?</a>
            </div>

        </form>

    </div>
@endsection
