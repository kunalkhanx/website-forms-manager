@extends('app')

@section('head')
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
@endsection

@section('main')
    <div class="container mx-auto max-w-screen-xl p-4" id="form">
        <div class="w-full max-w-sm mx-auto">
            <div class="mb-6 flex items-center">
                <a href="{{ route('fields') }}" class="p-4 pl-0">
                    <svg class="w-8 h-8" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 12h14M5 12l4-4m-4 4 4 4" />
                    </svg>
                </a>
                <h2 class="text-3xl">{{ $field->id ? 'Update' : 'Create new' }} field</h2>
            </div>

            <form action="{{ $field->id ? route('fields.do_update', ['field' => $field->id]) : route('fields.do_create') }}"
                class="flex flex-col gap-4" method="POST">
                @csrf
                @if ($field->id)
                    @method('PATCH')
                @endif
                <div class="form-control">
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name" placeholder="Enter unique field name"
                        value="{{ old('name', $field->name) }}">
                    @error('name')
                        <p class="err">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-control">
                    <label for="label">Label</label>
                    <input type="text" name="label" id="label" placeholder="Enter field label (optional)"
                        value="{{ old('label', $field->label) }}">
                    @error('label')
                        <p class="err">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-control">
                    <label for="placeholder">Placeholder</label>
                    <input type="text" name="placeholder" id="placeholder"
                        placeholder="Enter field placeholder (optional)"
                        value="{{ old('placeholder', $field->placeholder) }}">
                    @error('placeholder')
                        <p class="err">{{ $message }}</p>
                    @enderror
                </div>
                @php
                    $rules = [
                        'numeric' => false,
                        'file' => false,
                        'email' => false,
                        'boolean' => false,
                        'max' => 0,
                        'min' => 0,
                        'digits' => 0,
                        'in' => '',
                    ];
                    $existing_rules = old('validation_rules')
                        ? old('validation_rules')
                        : explode('|', $field->validation_rules);
                    foreach ($existing_rules as $rule) {
                        if (str_contains($rule, ':')) {
                            $val = explode(':', $rule);
                            $rules[$val[0]] = $val[1];
                        } elseif (old($rule)) {
                            $rules[$rule] = old($rule);
                        } else {
                            $rules[$rule] = true;
                        }
                    }
                @endphp

                <div class="flex flex-col gap-4">
                    <p class="block text-sm font-medium text-gray-900 dark:text-white">Validation rule</p>

                    <div class="flex items-center">
                        <input :disabled="email || file || boolean" v-model="numeric" id="vr-numeric" name="validation_rules[]"
                            type="checkbox" value="numeric"
                            class="v-checkbox">
                        <label for="vr-numeric"
                            class="v-label" :class="{disabled: email || file || boolean}">Number</label>
                    </div>

                    <div class="flex items-center">
                        <input :disabled="numeric || file || boolean" v-model="email" id="vr-email" name="validation_rules[]"
                            type="checkbox" value="email"
                            class="v-checkbox">
                        <label for="vr-email"
                            class="v-label" :class="{disabled: numeric || file || boolean}">Email</label>
                    </div>

                    <div class="flex items-center">
                        <input :disabled="email || numeric || boolean" v-model="file" id="vr-file" name="validation_rules[]"
                            type="checkbox" value="file"
                            class="v-checkbox">
                        <label for="vr-file" class="v-label" :class="{disabled: email || numeric || boolean}">File</label>
                    </div>

                    <div class="flex items-center">
                        <input :disabled="email || numeric || file" v-model="boolean" id="vr-checkbox" name="validation_rules[]"
                            type="checkbox" value="boolean"
                            class="v-checkbox">
                        <label for="vr-checkbox" class="v-label" :class="{disabled: email || numeric || file}">Checkbox (yes/no)</label>
                    </div>

                    <div class="flex items-center">
                        <input :disabled="boolean" v-model="max" id="vr-max" name="validation_rules[]" type="checkbox" value="max"
                            class="v-checkbox">
                        <label for="vr-max" class="v-label" :class="{disabled: boolean}">Max
                            @{{file ? 'Size (KB)' : 'Length'}}</label>
                        <input type="number" name="max" value="{{ $rules['max'] }}" :disabled="boolean"
                            class="v-input max-w-14">
                    </div>

                    <div class="flex items-center">
                        <input :disabled="boolean" v-model="min" id="vr-min" name="validation_rules[]"
                            type="checkbox" value="min"
                            class="v-checkbox">
                        <label for="vr-min" class="v-label" :class="{disabled: boolean}">Min
                            @{{file ? 'Size (KB)' : 'Length'}}</label>
                        <input type="number" name="min" value="{{ $rules['min'] }}" :disabled="boolean"
                            class="v-input max-w-14">
                    </div>

                    <div class="flex items-center">
                        <input :disabled="!numeric" v-model="digits" id="vr-digits" name="validation_rules[]" 
                            type="checkbox" value="digits"
                            class="v-checkbox">
                        <label for="vr-digits" :class="{disabled: !numeric}"
                            class="v-label">Digits</label>
                        <input type="number" name="digits" value="{{ $rules['digits'] }}" :disabled="!numeric"
                            class="v-input max-w-14">
                    </div>

                    <div class="flex items-center">
                        <input :disabled="boolean" v-model="inArr" id="vr-in" name="validation_rules[]"
                            type="checkbox" value="in"
                            class="v-checkbox">
                        <label for="vr-in" :class="{disabled: boolean}"
                            class="v-label">List</label>
                        <input type="text" name="in" value="{{ $rules['in'] }}" :disabled="boolean"
                            placeholder="Comma Separated Value(s)"
                            class="v-input max-w-64">
                    </div>


                </div>

                <div>
                    <button type="submit"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">{{ $field->id ? 'Update' : 'Create' }}</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const {createApp} = Vue

        createApp({
            data() {
                return {
                    numeric: {{ $rules['numeric'] ? 'true' : 'false' }},
                    email: {{ $rules['email'] ? 'true' : 'false' }},
                    file: {{ $rules['file'] ? 'true' : 'false' }},
                    boolean: {{ $rules['boolean'] ? 'true' : 'false' }},
                    max: {{ $rules['max'] ? 'true' : 'false' }},
                    min: {{ $rules['min'] ? 'true' : 'false' }},
                    digits: {{ $rules['digits'] ? 'true' : 'false' }},
                    inArr: {{ $rules['in'] ? 'true' : 'false' }}
                }
            },
        }).mount('#form')
    </script>
@endsection
