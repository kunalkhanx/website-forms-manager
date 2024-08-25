@extends('auth')

@section('title', 'Password Reset')

@section('main')
    <div class="w-full max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
        <form action="{{ route('do_forgot_password') }}" class="flex flex-col gap-4" method="POST">
            @csrf
            <h2 class="text-3xl">Password Reset</h2>

            <div class="form-control">
                <input type="text" id="username" name="username" placeholder="Username or email" autocomplete="off" />
            </div>

            <div class="flex justify-between items-center max-sm:flex-col gap-4">
                <button type="submit"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Sent recovery mail</button>
                <a href="{{route('login')}}" class="hover:text-blue-700">Back to login</a>
            </div>

        </form>

    </div>
@endsection
