@extends('app')

@section('main')
    <div class="container mx-auto max-w-screen-xl p-4 flex flex-col gap-6">

        <div class="grid grid-cols-4 gap-6">

            <div class="rounded-md shadow-md p-4 flex items-center gap-4 bg-rose-600 text-white">
                <span class="flex h-16 w-16 rounded-full bg-white/30 items-center justify-center">
                    <svg class="w-8 h-8" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 4h3a1 1 0 0 1 1 1v15a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h3m0 3h6m-3 5h3m-6 0h.01M12 16h3m-6 0h.01M10 3v4h4V3h-4Z" />
                    </svg>
                </span>
                <div>
                    <p class="text-4xl">{{ $total['forms'] }}</p>
                    <p>Total Forms</p>
                </div>
            </div>


            <div class="rounded-md shadow-md p-4 flex items-center gap-4 bg-amber-600 text-white">
                <span class="flex h-16 w-16 rounded-full bg-white/30 items-center justify-center">
                    <svg class="w-8 h-8 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                        height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15.2 6H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11.2a1 1 0 0 0 .747-.334l4.46-5a1 1 0 0 0 0-1.332l-4.46-5A1 1 0 0 0 15.2 6Z" />
                    </svg>
                </span>
                <div>
                    <p class="text-4xl">{{ $total['fields'] }}</p>
                    <p>Total Fields</p>
                </div>
            </div>

            <div class="rounded-md shadow-md p-4 flex items-center gap-4 bg-purple-600 text-white">
                <span class="flex h-16 w-16 rounded-full bg-white/30 items-center justify-center">
                    <svg class="w-8 h-8" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 6c0 1.657-3.134 3-7 3S5 7.657 5 6m14 0c0-1.657-3.134-3-7-3S5 4.343 5 6m14 0v6M5 6v6m0 0c0 1.657 3.134 3 7 3s7-1.343 7-3M5 12v6c0 1.657 3.134 3 7 3s7-1.343 7-3v-6" />
                    </svg>

                </span>
                <div>
                    <p class="text-4xl">{{ $total['data'] }}</p>
                    <p>Data Collected</p>
                </div>
            </div>


            <div class="rounded-md shadow-md p-4 flex items-center gap-4 bg-green-600 text-white">
                <span class="flex h-16 w-16 rounded-full bg-white/30 items-center justify-center">

                    <svg class="w-8 h-8 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                        height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-width="2"
                            d="M4.5 17H4a1 1 0 0 1-1-1 3 3 0 0 1 3-3h1m0-3.05A2.5 2.5 0 1 1 9 5.5M19.5 17h.5a1 1 0 0 0 1-1 3 3 0 0 0-3-3h-1m0-3.05a2.5 2.5 0 1 0-2-4.45m.5 13.5h-7a1 1 0 0 1-1-1 3 3 0 0 1 3-3h3a3 3 0 0 1 3 3 1 1 0 0 1-1 1Zm-1-9.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Z" />
                    </svg>

                </span>
                <div>
                    <p class="text-4xl">{{ $total['users'] }}</p>
                    <p>Users</p>
                </div>
            </div>

        </div>



        <div class="grid grid-cols-3 gap-6">


            {{-- TOP FORMS --}}
            <div
                class="p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 flex flex-col gap-4">
                <h2 class="text-lg font-medium">Top Forms</h2>

                <ul class="divide-y">
                    @foreach ($top_forms as $form)
                        <li class="py-2 flex items-center justify-between"><a href="{{route('forms.form_data', ['id' => $form->id])}}">{{ $form->name }}</a> <span class="text-green-500">{{ $form->data_count }}</span></li>
                    @endforeach
                </ul>

            </div>
            {{-- TOP FORMS --}}



            {{-- Fields --}}
            <div
                class="p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 flex flex-col gap-4">
                <h2 class="text-lg font-medium">Fields</h2>

                <ul class="divide-y">
                    @foreach ($fields as $field)
                    <li class="py-2 flex items-center justify-between"><a href="{{route('fields.do_update', ['field' => $field->id])}}">{{$field->label ? $field->label : $field->name}}</a></li>
                    @endforeach
                </ul>

            </div>
            {{-- Fields --}}


            {{-- Users --}}
            <div
                class="p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 flex flex-col gap-4">
                <h2 class="text-lg font-medium">Users</h2>

                <ul class="divide-y">
                    @foreach ($users as $user)
                    <li class="py-2 flex items-center justify-between"><a href="{{route('users.do_update', ['user' => $user->id])}}">{{$user->name}}</a></li>
                    @endforeach
                   
                </ul>

            </div>
            {{-- Users --}}
        </div>

    </div>
@endsection
