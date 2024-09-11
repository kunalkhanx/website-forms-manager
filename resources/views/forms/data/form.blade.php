@extends('app')

@section('title', $formData->id ? 'Update Form-Data' : 'Add Form-Data')

@section('main')
    <div class="container mx-auto max-w-screen-xl p-4">
        <div class="w-full max-w-sm mx-auto flex flex-col gap-6">
            <div class="flex items-center">
                <a href="{{route('forms.form_data', ['id' => $form->id])}}" class="p-4 pl-0">
                    <svg class="w-8 h-8" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 12h14M5 12l4-4m-4 4 4 4" />
                    </svg>
                </a>
                <div>
                    <h2 class="text-3xl">{{$formData->id ? 'Update' : 'Add'}} form data</h2>
                    <p>{{ $form->name }}</p>
                </div>
            </div>

            @php
                function getInputType($validation_rules): array
                {
                    $rules = explode('|', $validation_rules);
                    foreach ($rules as $value) {
                        if($value == 'numeric'){
                            return ['number'];
                        }
                        if($value == 'boolean'){
                            return ['checkbox'];
                        }
                        if($value == 'email'){
                            return ['email'];
                        }
                        if($value == 'file'){
                            return ['file'];
                        }
                        if (str_contains($value, 'max:')) {
                            $maxValue = (int) explode(':', $value)[1];
                            if ($maxValue > 255) {
                                return ['textarea'];
                            }
                            break;
                        }
                        if (str_starts_with($value, 'in:')) {
                            $valuesStr = explode(':', $value)[1];
                            $valuesArr = explode(',', $valuesStr);
                            return ['array', $valuesArr];
                        }
                    }

                    return ['text'];
                }
                $fData = $formData->data;
            @endphp

            <form action="{{$formData->id ? route('forms.do_update_data', ['formData' => $formData->id]) : route('forms.do_create_data', ['form' => $form->id])}}" class="flex flex-col gap-4" method="POST" enctype="multipart/form-data">
                @csrf
                @if($formData->id) @method('PATCH') @endif
                @foreach ($form->fields as $field)
                    @php $inputType = getInputType($field->validation_rules); @endphp
                    
                    @if ($inputType[0] == 'textarea')
                        <div class="form-control">
                            <label for="{{ $field->name }}">{{ $field->label }}</label>
                            <textarea id="{{ $field->name }}" placeholder="{{ $field->placeholder }}" rows="3" name="{{ $field->name }}">{{ old($field->name, !isset($fData[$field->name]) ? null : $fData[$field->name]) }}</textarea>
                            @error($field->name)
                                <p class="err">{{ $message }}</p>
                            @enderror
                        </div>
                    @elseif($inputType[0] == 'checkbox')
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input id="{{ $field->name }}" type="checkbox" value="1"
                                    {{ old($field->name, !isset($fData[$field->name]) ? null : $fData[$field->name]) ? 'checked' : '' }} name="{{ $field->name }}"
                                    class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800"
                                     />
                            </div>
                            <label for="{{ $field->name }}"
                                class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ $field->label }}</label>
                        </div>
                        @error($field->name)
                                <p class="err">{{ $message }}</p>
                        @enderror
                    @elseif($inputType[0] == 'array')
                        <div class="form-control">
                            <label for="{{ $field->name }}">{{ $field->label }}</label>
                            <select name="{{ $field->name }}" id="{{ $field->name }}">
                                <option value="">{{ $field->placeholder }}</option>
                                @foreach ($inputType[1] as $item)
                                    <option {{old($field->name, !isset($fData[$field->name]) ? null : $fData[$field->name]) === $item ? 'selected' : ''}} value="{{ $item }}">{{ ucfirst(strtolower($item)) }}</option>
                                @endforeach
                            </select>
                            @error($field->name)
                                <p class="err">{{ $message }}</p>
                            @enderror
                        </div>

                    @elseif($inputType[0] == 'file')
                        <div class="file-control">
                            <label for="{{$field->name}}">{{ $field->label }}</label>
                            <input type="file" name="{{$field->name}}" placeholder="{{$field->placeholder}}">
                            @error($field->name)
                                <p class="err">{{ $message }}</p>
                            @enderror
                        </div>
                    @else
                        <div class="form-control">
                            <label for="{{ $field->name }}">{{ $field->label }}</label>
                            <input type="{{ $inputType[0] }}" name="{{ $field->name }}" value="{{ old($field->name, !isset($fData[$field->name]) ? null : $fData[$field->name]) }}"
                                id="{{ $field->name }}" placeholder="{{ $field->placeholder }}">
                            @error($field->name)
                                <p class="err">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif
                @endforeach

                <div>
                    <button type="submit"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Save</button>
                </div>
            </form>
        </div>
    </div>
@endsection
