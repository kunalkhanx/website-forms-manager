@extends('app')

@section('title', $form->id ? 'Update From' : 'Create New Form')

@section('main')

<div class="container mx-auto max-w-screen-xl p-4">
    <div class="w-full max-w-sm mx-auto flex flex-col gap-6">

        <div class="flex items-center">
            <a href="{{route('forms')}}" class="p-4 pl-0">
                <svg class="w-8 h-8" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12l4-4m-4 4 4 4"/>
                </svg>                  
            </a>
            <h2 class="text-3xl">{{$form->id ? 'Update' : 'Create New'}} Form</h2>
        </div>

        <form action="{{$form->id ? route('forms.do_update', ['form' => $form->id]) : route('forms.do_create')}}" method="POST" class="flex flex-col gap-4">
            @csrf
            @if($form->id) @method('PATCH') @endif
            <div class="form-control">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" placeholder="Enter form name" value="{{old('name', $form->name)}}">
                @error('name')
                    <p class="err">{{$message}}</p>
                @enderror
            </div>
            <div class="form-control">
                <label for="description">Description</label>
                <textarea name="description" id="description" rows="2" placeholder="Enter form description (optional)">{{old('description', $form->description)}}</textarea>
                @error('description')
                    <p class="err">{{$message}}</p>
                @enderror
            </div>
            <div class="flex items-start">
                @php
                    if($form->status === NULL){
                        $form->status = 1;
                    }
                @endphp
                <div class="flex items-center h-5">
                    <input id="disabled" name="disabled" type="checkbox" value="1" {{old('disabled', ($form->status < 1)) ? 'checked' : ''}}
                        class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800" />
                </div>
                <label for="disabled" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Disabled</label>
            </div>
            <div class="flex items-start">
                <div class="flex items-center h-5">
                    <input id="public" name="public" type="checkbox" value="1" {{old('public', ($form->public)) ? 'checked' : ''}}
                        class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800" />
                </div>
                <label for="public" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Public</label>
            </div>
            <div>
                <button type="submit"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">{{$form->id ? 'Update' : 'Create'}}</button>
            </div>
        </form>
        @if($form->id)
        <hr>
        @if($form->public)
        <div class="">
            <p class="text-lg font-medium mb-2">API Endpoints</p>
            <p class="flex gap-3 text-xs"><span class="text-green-600">VIEW DATA </span>{{url('/')}}/api/forms/{form_data_id}</p>
            <p class="flex gap-3 text-xs"><span class="text-purple-600">CREATE DATA </span>{{route('forms.api.create', ['form' => $form->id])}}</p>
            <p class="flex gap-3 text-xs"><span class="text-amber-600">UPDATE DATA </span>{{route('forms.api.create', ['form' => $form->id])}}/{form_data_id}</p>
        </div>
        <hr>
        @endif
        
        <div class="flex flex-col gap-6">
            <h2 class="text-2xl">Fields</h2>

            <div class="relative overflow-x-auto shadow-md sm:rounded-lg mb-6">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-3 py-3">
                                Field
                            </th>
                            <th scope="col" class="px-3 py-3">
                                Required
                            </th>
                            <th scope="col" class="px-3 py-3">
                                Unique
                            </th>
                            <th scope="col" class="px-3 py-3">
                                Display
                            </th>
                            <th scope="col" class="px-3 py-3">
                                Delete
                            </th>
                        </tr>
                    </thead>
                    <tbody class="text-xs">
                        @foreach ($fields as $field)
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="px-3 py-4">
                                    {{ $field->name }}
                                </td>
                                <td class="px-3 py-4">
                                    {{ $field->pivot->is_required ? 'Yes' : 'No' }}
                                </td>
                                <td class="px-3 py-4">
                                    {{ $field->pivot->is_unique ? 'Yes' : 'No' }}
                                </td>
                                <td class="px-3 py-4">
                                    {{ $field->pivot->display ? 'Yes' : 'No' }}
                                </td>                      
                                <td class="px-3 py-4 flex items-center gap-3">
                                    <form action="{{route('forms.do_remove_field', ['form' => $form->id, 'field' => $field->id])}}" method="POST" class="confirm" data-prompt="Are you sure to remove the field?">
                                        @csrf
                                        <button type="submit" class="font-medium text-red-600 dark:text-red-500 hover:underline">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
    
                    </tbody>
                </table>
            </div>

            <form action="{{route('forms.do_add_field', ['form' => $form->id])}}" method="POST" class="flex flex-col gap-6">
                @csrf
                <h2 class="text-xl">Add Field</h2>
                <div class="form-control">
                    <label for="field_name">Field name</label>
                    <input type="text" name="field_name" id="field_name" placeholder="Enter field name" value="{{old('field_name')}}">
                    @error('field_name')
                        <p class="err">{{$message}}</p>
                    @enderror
                </div>

                <div class="flex items-start">
                    <div class="flex items-center h-5">
                      <input id="required" name="required" type="checkbox" value="1" class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800" {{old('required') ? 'checked' : ''}} />
                    </div>
                    <label for="required" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Required</label>
                    @error('required')
                        <p class="err">{{$message}}</p>
                    @enderror
                  </div>

                  <div class="flex items-start">
                    <div class="flex items-center h-5">
                      <input id="unique" name="unique" type="checkbox" value="1" class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800" {{old('unique') ? 'checked' : ''}} />
                    </div>
                    <label for="unique" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Unique</label>
                    @error('unique')
                        <p class="err">{{$message}}</p>
                    @enderror
                  </div>

                  <div class="flex items-start">
                    <div class="flex items-center h-5">
                      <input id="display" name="display" type="checkbox" value="1" class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800" checked />
                    </div>
                    <label for="display" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Display on table</label>
                    @error('display')
                        <p class="err">{{$message}}</p>
                    @enderror
                  </div>

                  <div>
                    <button type="submit"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Add</button>
                </div>

            </form>
        </div>
        @endif
    </div>
</div>

@endsection