@extends('app')

@section('main')
    <div class="container mx-auto max-w-screen-xl p-4 flex flex-col gap-6">

        <div class="flex max-sm:flex-col gap-6 justify-between items-center">
            <div class="flex items-center">
                <a href="{{route('forms')}}" class="p-4 pl-0">
                    <svg class="w-8 h-8" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 12h14M5 12l4-4m-4 4 4 4" />
                    </svg>
                </a>
                <h2 class="text-3xl">{{$form->name}}</h2>
            </div>

            <a href="{{route('forms.create_data', ['id' => $form->id])}}"
                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Add data</a>
        </div>


        <div class="flex items-end gap-3 justify-between">

            <form action="{{route('forms.form_data', ['id' => $form->id])}}" method="GET" class="w-full max-w-xs">
                <div class="relative w-full">
                    <button type="submit" class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z"/>
                        </svg>                          
                    </button>
                    <input name="search" type="text" id="search" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Search" value="{{request()->search}}">
                </div>
            </form>

           <form action="{{route('forms.form_data', ['id' => $form->id])}}" method="GET" class="flex items-end gap-3">
                <div class="form-control w-full max-w-36">
                    <label for="start_date">Start date</label>
                    <input type="date" name="start_date" id="start_date" value="{{request()->start_date}}">
                </div>

                <div class="form-control w-full max-w-36">
                    <label for="end_date">End date</label>
                    <input type="date" name="end_date" id="end_date" value="{{request()->end_date}}">
                </div>

                <div class="flex gap-2 items-center">
                    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Filter</button>
                    <a href="{{route('forms.form_data', ['id' => $form->id])}}" type="button" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">Reset</a>
                </div>
           </form>

        </div>



        <div class="relative overflow-x-auto shadow-md sm:rounded-lg mb-6">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            #ID
                        </th>
                        @foreach ($form->fields as $field)
                        @if($field->pivot->display)
                        <th scope="col" class="px-6 py-3">
                            {{$field->label}}
                        </th>
                        @endif
                        @endforeach
                        <th scope="col" class="px-6 py-3">
                            Created At
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        function printData($dataToPrint, $validation_rules){
                            $rules = explode('|', $validation_rules);
                            if(in_array('boolean', $rules)){
                                // var_dump($dataToPrint);
                                return $dataToPrint == 1 ? 'Yes' : 'No';
                            }
                            return $dataToPrint;
                        }
                    @endphp
                    @foreach ($formData as $row)
                        @php
                            $data = json_decode($row->data, true);
                        @endphp
                        <tr
                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <th scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $row->id }}
                            </th>
                            @foreach ($form->fields as $field)
                            @if($field->pivot->display)
                            <td class="px-6 py-4">
                                {{ printData(!isset($data[$field->name]) ? null : $data[$field->name], $field->validation_rules) }}
                            </td>
                            @endif
                            @endforeach
                            <td class="px-6 py-4">
                                {{ $row->created_at }}
                            </td>             
                            <td class="px-6 py-4 flex items-center gap-3">
                                <a href="{{route('forms.show_data', ['id' => $form->id, 'formData' => $row->id])}}" class="font-medium text-green-600 dark:text-green-500 hover:underline">
                                    View
                                </a>
                                <a href="{{route('forms.update_data', ['id' => $form->id, 'formData' => $row->id])}}"
                                    class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                                <form action="{{route('forms.do_delete_data', ['formData' => $row->id])}}" method="POST" class="confirm" data-prompt="Are you sure to delete the entry?">
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
                <a href="{{$formData->previousPageUrl()}}" class="flex items-center justify-center px-3 h-8 ms-0 leading-tight text-gray-500 bg-white border border-e-0 border-gray-300 rounded-s-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Previous</a>
              </li>
              @for($i = 1; $i <= $formData->lastPage(); $i++)
              <li>
                <a href="{{$formData->url($i)}}" class="{{$i == $formData->currentPage() ? 'flex items-center justify-center px-3 h-8 text-blue-600 border border-gray-300 bg-blue-50 hover:bg-blue-100 hover:text-blue-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white' : 'flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white'}}">{{$i}}</a>
              </li>
              @endfor
              
              <li>
                <a href="{{$formData->nextPageUrl()}}" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 rounded-e-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Next</a>
              </li>
            </ul>
          </nav>
    </div>
@endsection
