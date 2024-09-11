<?php

namespace App\Http\Controllers;

use App\Models\Field;
use App\Models\File;
use App\Models\Form;
use App\Models\FormData;
use App\Models\FormField;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FormController extends Controller
{

    /**
     * Function - Forms table page
     * 
     * @return View
     */
    public function index()
    {
        $forms = Form::where('status', '>=', 0)->with(['user' => function ($query) {
            $query->select(['id', 'name']);
        }])->withCount('fields')->withCount('data')->latest()->paginate(10);
        return view('forms.index', ['forms' => $forms]);
    }

    /**
     * Function - Create form page
     * 
     * @return View
     */
    public function create()
    {
        return view('forms.form', ['form' => new Form]);
    }

    /**
     * Function - Update exisitng form page
     * 
     * @param Form $form
     * 
     * @return View
     */
    public function update(Form $form)
    {
        if (!$form) {
            return response('', 404);
        }
        $fields = $form->fields()->get();
        return view('forms.form', ['form' => $form, 'fields' => $fields]);
    }

    /**
     * Function - Submitted form data table page
     * 
     * @param Request $request
     * @param Int $id
     * 
     * @return View
     */
    public function form_data(Request $request, $id)
    {
        $form = Form::where('id', $id)->with('user')->with('fields')->first();
        if (!$form) {
            return response('', 404);
        }
        $query = $form->data();
        if ($request->start_date && $request->end_date) {
            $query->whereDate('created_at', '>=', $request->start_date)->whereDate('created_at', '<=', $request->end_date);
        }
        if ($request->search) {
            $query->where('data', 'LIKE', '%' . $request->search . '%');
        }
        $formData = $query->paginate(10);
        return view('forms.data.index', ['form' => $form, 'formData' => $formData]);
    }

    /**
     * Function - Dynamic form to create form-data
     * 
     * @param Int $id Form id
     * 
     * @return View
     */
    public function create_data($id)
    {
        $form = Form::where('id', $id)->with('fields')->first();
        return view('forms.data.form', ['form' => $form, 'formData' => new FormData]);
    }

    /**
     * Function - Dynamic form to update exisitng form-data
     * 
     * @param Int $id Form id
     * @param FormData $formData
     * 
     * @return View
     */
    public function update_data($id, FormData $formData)
    {
        $form = Form::where('id', $id)->with('fields')->first();
        if (!$formData || !$form) {
            return response('', 404);
        }
        return view('forms.data.form', ['form' => $form, 'formData' => $formData]);
    }

    /**
     * Function - Show a single form-data entry
     * 
     * @param Int $id Form id
     * @param FormData $formData
     * 
     * @return View
     */
    public function show_data($id, FormData $formData)
    {
        $form = Form::where('id', $id)->with('fields')->first();
        if (!$formData || !$form) {
            return response('', 404);
        }
        return view('forms.data.show', ['form' => $form, 'formData' => $formData]);
    }

    /**
     * Function - Exported form-data as CSV
     * 
     * @param Request $request
     * @param Int $id Form id
     * 
     * @return Response
     */
    public function do_export_data(Request $request, $id)
    {
        $form = Form::where('id', $id)->with('user')->with('fields')->first();
        if (!$form) {
            return response('', 404);
        }
        $query = $form->data();
        if ($request->start_date && $request->end_date) {
            $query->whereDate('created_at', '>=', $request->start_date)->whereDate('created_at', '<=', $request->end_date);
        }
        if ($request->search) {
            $query->where('data', 'LIKE', '%' . $request->search . '%');
        }
        $formData = $query->take(1000)->get();
        $fields = [];
        foreach ($form->fields as $field) {
            $fields[] = $field->label ? $field->label : $field->name;
        }
        $handle = fopen('php://memory', 'r+');
        fputcsv($handle, ['Id', ...$fields, 'Created At', 'Updated At']);
        foreach ($formData as $data) {
            $dataObject = json_decode($data->data, true);
            $dataRow = [$data->id];
            foreach ($form->fields as $field) {
                if (str_contains($field->validation_rules, 'boolean')) {
                    $dataRow[] = isset($dataObject[$field->name]) ? ($dataObject[$field->name] == 1 ? 'Yes' : 'No') : 'No';
                } else {
                    $dataRow[] = isset($dataObject[$field->name]) ? $dataObject[$field->name] : '';
                }
            }
            $dataRow[] = $data->created_at;
            $dataRow[] = $data->updated_at;
            fputcsv($handle, $dataRow);
        }

        rewind($handle);
        $contents = stream_get_contents($handle);
        fclose($handle);

        return response($contents)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="form_data.csv"')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    
    
    /**
     * Function - Create new form action
     * 
     * @param Request $request
     * 
     * @return Redirect
     */
    public function do_create(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3|max:160',
            'description' => 'nullable|max:255',
            'public' => 'boolean',
            'disabled' => 'boolean'
        ]);
        $user = Auth::user();
        $form = new Form;
        $form->user_id = $user->id;
        $form->name = $request->name;
        $form->description = $request->description;
        if ($request->disabled) {
            $form->status = 0;
        } else {
            $form->status = 1;
        }
        if ($request->public) {
            $form->public = true;
        } else {
            $form->public = false;
        }
        $result = $form->save();
        if (!$result) {
            return redirect()->back()->withInput()->with('error', 'Unable to create the form!');
        }
        return redirect()->route('forms.update', ['form' => $form->id])->with('success', 'Form created successfully!');
    }

    /**
     * Function - Update exisitng form action
     * 
     * @param Request $request
     * @param Form $form
     * 
     * @return Redirect
     */
    public function do_update(Request $request, Form $form)
    {
        if (!$form) {
            return response('', 404);
        }
        $request->validate([
            'name' => 'required|min:3|max:160',
            'description' => 'nullable|max:255',
            'public' => 'boolean',
            'disabled' => 'boolean'
        ]);
        $user = Auth::user();
        $form->user_id = $user->id;
        $form->name = $request->name;
        $form->description = $request->description;
        if ($request->disabled) {
            $form->status = 0;
        } else {
            $form->status = 1;
        }
        if ($request->public) {
            $form->public = true;
        } else {
            $form->public = false;
        }
        $result = $form->save();
        if (!$result) {
            return redirect()->back()->withInput()->with('error', 'Unable to update the form!');
        }
        return redirect()->back()->with('success', 'Form updated successfully!');
    }

    /**
     * Function - Move form to trash
     * 
     * @param Form $form
     * 
     * @return Redirect
     */
    public function do_delete(Form $form)
    {
        if (!$form) {
            return response('', 404);
        }
        $form->status = -1;
        $result = $form->save();
        if (!$result) {
            return redirect()->back()->withInput()->with('error', 'Unable to delete the form!');
        }
        return redirect()->back()->with('success', 'Form deleted successfully!');
    }

    /**
     * Function - Attch a field to a form
     * 
     * @param Request $request
     * @param Form $form
     * 
     * @return Redirect
     */
    public function do_add_field(Request $request, Form $form)
    {
        if (!$form) {
            return response('', 404);
        }
        $request->validate([
            'field_name' => 'required|min:3|max:25',
            'required' => 'nullable|boolean',
            'unique' => 'nullable|boolean',
            'display' => 'nullable|boolean'
        ]);

        $field = Field::where('name', $request->field_name)->where('status', '>', 0)->first();

        if (!$field) {
            return redirect()->back()->withInput()->with('error', 'Fiend name not found!');
        }

        $form->fields()->attach(
            ['field_id' => $field->id],
            [
                'is_required' => $request->required ? true : false,
                'is_unique' => $request->unique ? true : false,
                'display' => $request->display ? true : false
            ]
        );
        return redirect()->back()->with('success', 'Filed added to the form successfully.');
    }

    /**
     * Function - Detach a field form a form
     * 
     * @param Form $form
     * @param Field $field
     * 
     * @return Redirect
     */
    public function do_remove_field(Form $form, Field $field)
    {
        if (!$form || !$field) {
            return response('', 404);
        }
        $result = FormField::where('form_id', $form->id)->where('field_id', $field->id)->delete();
        if (!$result) {
            return redirect()->back()->with('error', 'Unable to remove the field!');
        }
        return redirect()->back()->with('success', 'Field removed successfully!');
    }

    /**
     * Function - Create new form-data action
     * 
     * @param Request $request
     * @param Form $form
     * 
     * @return Redirect
     */
    public function do_create_data(Request $request, Form $form)
    {
        if (!$form) {
            return response('', 404);
        }
        $fields = $form->fields()->get();
        $validation_rules = [];
        $data = [];
        $files = [];
        foreach ($fields as $field) {
            $validation_rules[$field->name] = ($field->pivot->is_required ? 'required|' : '') . $field->validation_rules;
            if ($field->pivot->is_unique && $request->{$field->name}) {
                $uniqueResult = FormData::where('form_id', $form->id)->where('data', 'LIKE', '%"email": "' . $request->{$field->name} . '"%')->first();
                if ($uniqueResult) {
                    return redirect()->back()->withInput()->withErrors([$field->name => $field->label . ' is already exists.']);
                }
            }
            $data[$field->name] = $request->get($field->name);
            if (str_contains($field->validation_rules, 'file')) {
                $files[] = $field->name;
            }
        }
        $request->validate($validation_rules);
        $date = date('d-m-Y');
        foreach ($files as $file_id) {
            if ($request->file($file_id)->isValid()) {
                $file = $request->file($file_id);
                $file_name = $file->store('uploads/' . $file_id . '/' . $date);

                $fileModel = new File;
                $fileModel->path = $file_name;
                $fileModel->size = $file->getSize();
                $fileModel->mime = $file->getMimeType();
                $fileResult = $fileModel->save();
                if(!$fileResult){
                    return redirect()->back()->withInput()->with('error', 'Unable to upload file!');
                }
                $data[$file_id] = $fileModel->id;
            }
        }
        $formData = new FormData;
        $formData->form_id = $form->id;
        $formData->data = $data;
        $result = $formData->save();
        if (!$result) {
            return redirect()->back()->withInput()->with('error', 'Unable to add data!');
        }
        return redirect()->back()->with('success', 'Data added successfully!');
    }

    /**
     * Function - Update exisitng form-data action
     * 
     * @param Request $request
     * @param FormData $formData
     * 
     * @return Redirect
     */
    public function do_update_data(Request $request, FormData $formData)
    {
        if (!$formData) {
            return response('', 404);
        }
        $form = $formData->form()->first();
        if (!$form) {
            return response('', 404);
        }
        $fields = $form->fields()->get();
        $validation_rules = [];
        $data = [];
        $files = [];
        foreach ($fields as $field) {
            $validation_rules[$field->name] = ($field->pivot->is_required && !str_contains($field->validation_rules, 'file') ? 'required|' : '') . $field->validation_rules;
            if ($field->pivot->is_unique && $request->{$field->name}) {
                $uniqueResult = FormData::where('form_id', $form->id)->where('data', 'LIKE', '%"email": "' . $request->{$field->name} . '"%')->where('id', '!=', $formData->id)->first();
                if ($uniqueResult) {
                    return redirect()->back()->withInput()->withErrors([$field->name => $field->label . ' is already exists.']);
                }
            }

            $data[$field->name] = $request->get($field->name);
            if (str_contains($field->validation_rules, 'file') && $request->file($field->name)) {
                $files[] = $field->name;
            }
        }
        $request->validate($validation_rules);
        $date = date('d-m-Y');
        foreach ($files as $file_id) {
            if ($request->file($file_id)->isValid()) {
                $file = $request->file($file_id);
                $file_name = $file->store('uploads/' . $file_id . '/' . $date);

                $fileModel = new File;
                $fileModel->path = $file_name;
                $fileModel->size = $file->getSize();
                $fileModel->mime = $file->getMimeType();
                $fileResult = $fileModel->save();
                if(!$fileResult){
                    return redirect()->back()->withInput()->with('error', 'Unable to upload file!');
                }
                $data[$file_id] = $fileModel->id;
            }
        }
        $formData->data = $data;
        $result = $formData->save();
        if (!$result) {
            return redirect()->back()->with('error', 'Unable to updated data!');
        }
        return redirect()->back()->with('success', 'Data updated successfully!');
    }

    /**
     * Function - Delete a form-data
     * 
     * @param FormData $formData
     * 
     * @return Redirect
     */
    public function do_delete_data(FormData $formData)
    {
        if (!$formData) {
            return response('', 404);
        }
        $result = $formData->delete();
        if (!$result) {
            return redirect()->back()->with('error', 'Unable to delete data!');
        }
        return redirect()->back()->with('success', 'Data deleted successfully!');
    }
}
