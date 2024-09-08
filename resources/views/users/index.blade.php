@extends('app')

@section('title', 'Users')

@section('main')
    <div class="container mx-auto max-w-screen-xl p-4">

        <div class="mb-6 flex max-sm:flex-col gap-6 justify-between items-center">
            <h2 class="text-3xl">Users</h2>

            <a href="{{ route('users.create') }}"
                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Create
                User</a>
        </div>



        <div class="relative overflow-x-auto shadow-md sm:rounded-lg mb-6">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            #ID
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Name
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Username
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Email
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Role
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr
                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <th scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $user->id }}
                            </th>
                            <td class="px-6 py-4">
                                {{ $user->name }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $user->username }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $user->email }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $user->status > 0 ? 'Active' : 'No Active' }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $user->is_super ? 'Super Admin' : 'Admin' }}
                            </td>
                            <td class="px-6 py-4 flex items-center gap-3">
                                <a href="{{route('users.update', ['user' => $user->id])}}"
                                    class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                                {{-- <a href="#"
                                    class="font-medium text-red-600 dark:text-red-500 hover:underline">Delete</a> --}}
                                <form action="{{route('users.do_delete', ['user' => $user->id])}}" method="POST" class="confirm" data-prompt="Are you sure to delete the user?">
                                    @csrf
                                    <button type="submit" class="font-medium text-red-600 dark:text-red-500 hover:underline">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>


        <nav aria-label="Page navigation example">
            <ul class="inline-flex -space-x-px text-sm">
              <li>
                <a href="{{$users->appends(request()->except('page'))->previousPageUrl()}}" class="flex items-center justify-center px-3 h-8 ms-0 leading-tight text-gray-500 bg-white border border-e-0 border-gray-300 rounded-s-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Previous</a>
              </li>
              @for($i = 1; $i <= $users->lastPage(); $i++)
              <li>
                <a href="{{$users->appends(request()->except('page'))->url($i)}}" class="{{$i == $users->currentPage() ? 'flex items-center justify-center px-3 h-8 text-blue-600 border border-gray-300 bg-blue-50 hover:bg-blue-100 hover:text-blue-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white' : 'flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white'}}">{{$i}}</a>
              </li>
              @endfor
              
              <li>
                <a href="{{$users->appends(request()->except('page'))->nextPageUrl()}}" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 rounded-e-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Next</a>
              </li>
            </ul>
          </nav>
    </div>
@endsection
