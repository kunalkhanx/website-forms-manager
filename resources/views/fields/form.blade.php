@extends('app')

@section('main')

<div class="container mx-auto max-w-screen-xl p-4">
    <div class="w-full max-w-sm mx-auto">
        <div class="mb-6 flex items-center">
            <a href="{{route('fields')}}" class="p-4 pl-0">
                <svg class="w-8 h-8" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12l4-4m-4 4 4 4"/>
                </svg>                  
            </a>
            <h2 class="text-3xl">{{$field->id ? 'Update' : 'Create new'}} field</h2>
        </div>

        <form action="{{$field->id ? route('fields.do_update', ['field' => $field->id]) : route('fields.do_create')}}" class="flex flex-col gap-4" method="POST">
            @csrf
            @if($field->id) @method('PATCH') @endif
            <div class="form-control">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" placeholder="Enter unique field name" value="{{old('name', $field->name)}}">
                @error('name')
                    <p class="err">{{$message}}</p>
                @enderror
            </div>

            <div class="form-control">
                <label for="label">Label</label>
                <input type="text" name="label" id="label" placeholder="Enter field label (optional)" value="{{old('label', $field->label)}}">
                @error('label')
                    <p class="err">{{$message}}</p>
                @enderror
            </div>

            <div class="form-control">
                <label for="placeholder">Placeholder</label>
                <input type="text" name="placeholder" id="placeholder" placeholder="Enter field placeholder (optional)" value="{{old('placeholder', $field->placeholder)}}">
                @error('placeholder')
                    <p class="err">{{$message}}</p>
                @enderror
            </div>

            {{-- <div class="form-control">
                <label for="validation_rules">Validation rules</label>
                <textarea placeholder="Enter validation rules ..." name="validation_rules" id="validation_rules" rows="2">{{old('validation_rules', $field->validation_rules)}}</textarea>
                @error('validation_rules')
                    <p class="err">{{$message}}</p>
                @enderror
            </div> --}}
            @php
                $rules = ['numeric' => false, 'email' => false, 'boolean' => false, 'max' => 0, 'min' => 0, 'digits' => 0, 'in' => ''];
                $existing_rules = old('validation_rules') ? old('validation_rules') : explode('|', $field->validation_rules);
                foreach ($existing_rules as $rule) {
                    if(str_contains($rule, ':')){
                        $val = explode(':', $rule);
                        $rules[$val[0]] = $val[1];
                    }else if(old($rule)){
                        $rules[$rule] = old($rule);
                    }else {
                        $rules[$rule] = true;
                    }
                }
                // print_r($rules);
            @endphp

            <div class="flex flex-col gap-4">
                <p class="block text-sm font-medium text-gray-900 dark:text-white">Validation rule</p>
                
                <div class="flex items-center">
                    <input id="vr-numeric" name="validation_rules[]" {{$rules['numeric'] ? 'checked' : ''}} type="checkbox" value="numeric" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="vr-numeric" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Number</label>
                </div>

                <div class="flex items-center">
                    <input id="vr-email" name="validation_rules[]" {{$rules['email'] ? 'checked' : ''}} type="checkbox" value="email" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="vr-email" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Email</label>
                </div>

                <div class="flex items-center">
                    <input id="vr-checkbox" name="validation_rules[]" {{$rules['boolean'] ? 'checked' : ''}} type="checkbox" value="boolean" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="vr-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Checkbox (yes/no)</label>
                </div>

                <div class="flex items-center">
                    <input id="vr-max" name="validation_rules[]" type="checkbox" {{$rules['max'] ? 'checked' : ''}}  value="max" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="vr-max" class="mx-2 text-sm font-medium text-gray-900 dark:text-gray-300">Max Length</label>
                    <input type="number" name="max" value="{{$rules['max']}}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-14 p-1 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-50">
                </div>

                <div class="flex items-center">
                    <input id="vr-min" name="validation_rules[]" {{$rules['min'] ? 'checked' : ''}} type="checkbox" value="min" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="vr-min" class="mx-2 text-sm font-medium text-gray-900 dark:text-gray-300">Min Length</label>
                    <input type="number" name="min" value="{{$rules['min']}}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-14 p-1 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-50">
                </div>

                <div class="flex items-center">
                    <input id="vr-digits" name="validation_rules[]" {{$rules['digits'] ? 'checked' : ''}} type="checkbox" value="digits" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="vr-digits" class="mx-2 text-sm font-medium text-gray-900 dark:text-gray-300">Digits</label>
                    <input type="number" name="digits" value="{{$rules['digits']}}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-14 p-1 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-50">
                </div>

                <div class="flex items-center">
                    <input id="vr-in" name="validation_rules[]" {{$rules['in'] ? 'checked' : ''}} type="checkbox" value="in" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="vr-in" class="mx-2 text-sm font-medium text-gray-900 dark:text-gray-300">List</label>
                    <input type="text" name="in" value="{{$rules['in']}}" placeholder="Comma Separated Value(s)" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full max-w-64 p-1 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-50">
                </div>



            </div>

            <div>
                <button type="submit"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">{{$field->id ? 'Update' : 'Create'}}</button>
            </div>
        </form>
    </div>
</div>

@endsection