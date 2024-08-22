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

            <div class="form-control">
                <label for="validation_rules">Validation rules</label>
                <textarea placeholder="Enter validation rules ..." name="validation_rules" id="validation_rules" rows="2">{{old('validation_rules', $field->validation_rules)}}</textarea>
                @error('validation_rules')
                    <p class="err">{{$message}}</p>
                @enderror
            </div>

            <div>
                <button type="submit"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">{{$field->id ? 'Update' : 'Create'}}</button>
            </div>
        </form>
    </div>
</div>

@endsection