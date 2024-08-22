<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    <title>Login</title>
</head>

<body>
    {{view('partials.alert')}}
    <div class="w-screen h-screen flex items-center justify-center px-4">



        <div
            class="w-full max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
           <form action="{{route('do_login')}}" class="flex flex-col gap-4" method="POST">
            @csrf
            <h2 class="text-3xl">Login</h2>

            <div class="form-control">
                <input type="text" id="username" name="username" placeholder="Username" autocomplete="off" />
            </div>

            <div class="form-control">
                <input type="password" id="password" name="password" placeholder="Password" />
            </div>

            <div>
                <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Login</button>
            </div>

           </form>

        </div>


    </div>



    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.js"></script>
</body>

</html>
