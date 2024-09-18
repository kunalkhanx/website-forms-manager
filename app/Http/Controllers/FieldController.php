<?php

namespace App\Http\Controllers;

use App\Models\Field;
use Illuminate\Http\Request;

class FieldController extends Controller
{

     /**
     * Function - Fields table page
     * 
     * @return View
     */
    public function index(){
        $fields = Field::where('status', '>=', 0)->latest()->paginate(10);
        return view('fields.index', ['fields' => $fields]);
    }

     /**
     * Function - Create new field page
     * 
     * @return View
     */
    public function create(){
        return view('fields.form', ['field' => new Field]);
    }

     /**
     * Function - Update existing field page
     * 
     * @param Field $field
     * 
     * @return View
     */
    public function update(Field $field){
        if(!$field){
            return response('', 404);
        }
        return view('fields.form', ['field' => $field]);
    }


    /**
     * Function - Create field action
     * 
     * @param Request $request
     * 
     * @return Redirect
     */
    public function do_create(Request $request){
        $request->validate([
            'name' => 'required|min:3|max:25|unique:fields,name',
            'label' => 'nullable|max:50',
            'placeholder' => 'nullable|max:100',
            'validation_rules' => 'array'
        ]);
        if(!$request->validation_rules){
            $request->validation_rules = [];
        }
        $rules = "";
        foreach($request->validation_rules as $rule){
            if($rule === 'max' && $request->max){
                $rules .= "|{$rule}:{$request->max}";
            }else if($rule === 'min' && $request->min){
                $rules .= "|{$rule}:{$request->min}";
            }else if($rule === 'in' && $request->in){
                $rules .= "|{$rule}:{$request->in}";
            }else if($rule === 'digits' && $request->digits){
                $rules .= "|{$rule}:{$request->digits}";
            }else if($rule === 'file' && $request->mimes){
                $rules .= "|file|mimes:{$request->mimes}";
            }else{
                $rules .= "|{$rule}";
            }
        }
        $rules = substr($rules, 1);
        $field = new Field;
        $field->name = $request->name;
        $field->label = $request->label;
        $field->placeholder = $request->placeholder;
        $field->validation_rules = $rules;
        $result = $field->save();
        if(!$result){
            return redirect()->back()->withInput()->with('error', 'Unable to create the field!');
        }
        return redirect()->route('fields.do_update', $field->id)->with('success', 'Field created successfully!');
    }

    /**
     * Function - Update existing field action
     * 
     * @param Request $request
     * @param Field $field
     * 
     * @return Redirect
     */
    public function do_update(Request $request, Field $field){
        if(!$field){
            return response('', 404);
        }
        $request->validate([
            'name' => 'required|min:3|max:25|unique:fields,name,' . $field->id . ',id',
            'label' => 'nullable|max:50',
            'placeholder' => 'nullable|max:100',
            'validation_rules' => 'array'
        ]);
        $rules = "";
        foreach($request->validation_rules as $rule){
            if($rule === 'max' && $request->max){
                $rules .= "|{$rule}:{$request->max}";
            }else if($rule === 'min' && $request->min){
                $rules .= "|{$rule}:{$request->min}";
            }else if($rule === 'in' && $request->in){
                $rules .= "|{$rule}:{$request->in}";
            }else if($rule === 'digits' && $request->digits){
                $rules .= "|{$rule}:{$request->digits}";
            }else if($rule === 'file' && $request->mimes){
                $rules .= "|file|mimes:{$request->mimes}";
            }else{
                $rules .= "|{$rule}";
            }
        }
        $rules = substr($rules, 1);
        $field->name = $request->name;
        $field->label = $request->label;
        $field->placeholder = $request->placeholder;
        $field->validation_rules = $rules;
        $result = $field->save();
        if(!$result){
            return redirect()->back()->withInput()->with('error', 'Unable to update the field!');
        }
        return redirect()->back()->with('success', 'Field updated successfully!');
    }

    /**
     * Function - Field will moved to trash
     * 
     * @param Field $field
     * 
     * @return Redirect
     */
    public function do_delete(Field $field){
        if(!$field){
            return response('', 404);
        }
        $field->status = -1;
        $result = $field->save();
        if(!$result){
            return redirect()->back()->withInput()->with('error', 'Unable to delete the field!');
        }
        return redirect()->back()->with('success', 'Field deleted successfully!');
    }
}
