<?php

namespace App\Http\Controllers;

use App\Models\Field;
use App\Models\Form;
use App\Models\FormData;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    
     /**
     * Function - Dashboard page view
     * 
     * @return View
     */
    public function index(){

        $total = [
            'forms' => Form::all()->count(),
            'fields' => Field::all()->count(),
            'data' => FormData::all()->count(),
            'users' => User::all()->count()
        ];

        $top_forms = Form::withCount('data')->orderBy('data_count', 'DESC')->take(5)->get();
        $fields = Field::latest()->take(5)->get();
        $users = User::latest()->take(5)->get();
        return view('dashboard', ['total' => $total, 'top_forms' => $top_forms, 'fields' => $fields, 'users' => $users]);
    }
}
