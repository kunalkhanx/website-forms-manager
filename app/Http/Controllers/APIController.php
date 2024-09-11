<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\FormData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class APIController extends Controller
{
    public function form($id){
        $form = Form::where('id', $id)->where('public', true)->with('fields')->first();
        if(!$form){
            return response('', 404);
        }
        return response()->json($form);
    }

    public function data(Form $form){
        if(!$form || !$form->public){
            return response('', 404);
        }
        $data = $form->data()->paginate(15);
        return response()->json($data);
    }

    public function create_data(Request $request, Form $form){
        if(!$form || !$form->public){
            return response('', 404);
        }
        $fields = $form->fields()->get();
        $validation_rules = [];
        $data = [];
        foreach ($fields as $field) {
            $validation_rules[$field->name] = ($field->pivot->is_required ? 'required|' : '') . $field->validation_rules;
            if ($field->pivot->is_unique && $request->{$field->name}) {
                $uniqueResult = FormData::where('form_id', $form->id)->where('data', 'LIKE', '%"email": "' . $request->{$field->name} . '"%')->first();
                if ($uniqueResult) {
                    return response()->json([
                        'message' => 'Invalid form data.',
                        'errors' => [
                            $field->name => $field->label . ' is already exists.'
                        ]
                    ], 400);
                }
            }
            $data[$field->name] = $request->get($field->name);
        }
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid form data.',
                'errors' => $validator->errors()
            ], 400);
        }
        $formData = new FormData;
        $formData->form_id = $form->id;
        $formData->data = $data;
        $result = $formData->save();
        if (!$result) {
            return response()->json([
                'message' => 'Unable to add data!'
            ], 500);
        }
        return response()->json([
            'message' => 'Data added successfully!',
            'data' => $formData
        ], 201);
    }


    public function update_data(Request $request, Form $form, FormData $formData)
    {
        if (!$formData || !$form || !$form->public) {
            return response('', 404);
        }
        $fields = $form->fields()->get();
        $validation_rules = [];
        $data = [];
        foreach ($fields as $field) {
            $validation_rules[$field->name] = ($field->pivot->is_required ? 'required|' : '') . $field->validation_rules;
            if ($field->pivot->is_unique && $request->{$field->name}) {
                $uniqueResult = FormData::where('form_id', $form->id)->where('data', 'LIKE', '%"email": "' . $request->{$field->name} . '"%')->where('id', '!=', $formData->id)->first();
                if ($uniqueResult) {
                    return response()->json([
                        'message' => 'Invalid form data.',
                        'errors' => [
                            $field->name => $field->label . ' is already exists.'
                        ]
                    ], 400);
                }
            }

            $data[$field->name] = $request->get($field->name);
        }

        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid form data.',
                'errors' => $validator->errors()
            ], 400);
        }

        $formData->data = $data;
        $result = $formData->save();

        if (!$result) {
            return response()->json([
                'message' => 'Unable to update data!'
            ], 500);
        }
        return response()->json([
            'message' => 'Data updated successfully!',
            'data' => $formData
        ], 200);
    }
}
