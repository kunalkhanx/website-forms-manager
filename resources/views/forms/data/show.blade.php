@extends('app')

@section('title', 'Form-Data View')

@section('main')

<div class="container mx-auto max-w-2xl p-4 flex flex-col gap-6">

    <div class="flex items-center">
        <a href="{{route('forms.form_data', ['id' => $form->id])}}" class="p-4 pl-0">
            <svg class="w-8 h-8" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M5 12h14M5 12l4-4m-4 4 4 4" />
            </svg>
        </a>
        <div>
            <h2 class="text-3xl"># {{$formData->id}}</h2>
            <p>{{ $form->name }}</p>
        </div>
    </div>

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Field
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Value
                    </th>
                </tr>
            </thead>
            @php
                $data = json_decode($formData->data, true);
                function printData($dataToPrint, $validation_rules){
                    if(!$dataToPrint){
                        return '';
                    }
                    $rules = explode('|', $validation_rules);
                    if(in_array('boolean', $rules)){
                        return $dataToPrint == 1 ? 'Yes' : 'No';
                    }
                    if(in_array('file', $rules)){
                        return '<a href="' . route('file_download', ['file' => $dataToPrint]) . '" class="text-blue-600 hover:text-blue-700 hover:underline">Download</a>';
                    }
                    return $dataToPrint;
                }
            @endphp
            <tbody>
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <th scope="row"
                            class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            Entry Id
                        </th>
                        <td class="px-6 py-4">
                            {{ $formData->id }}
                        </td>                       
                    </tr>
                    @foreach ($form->fields as $field)
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <th scope="row"
                            class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{$field->label ? $field->label : $field->name}}
                        </th>
                        <td class="px-6 py-4">
                            {!! printData(!isset($data[$field->name]) ? null : $data[$field->name], $field->validation_rules) !!}
                        </td>                       
                    </tr>
                    @endforeach
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <th scope="row"
                            class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            Created At
                        </th>
                        <td class="px-6 py-4">
                            {{ $formData->created_at }}
                        </td>                       
                    </tr>
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <th scope="row"
                            class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            Updated At
                        </th>
                        <td class="px-6 py-4">
                            {{ $formData->updated_at }}
                        </td>                       
                    </tr>
            </tbody>
        </table>
    </div>

</div>

@endsection