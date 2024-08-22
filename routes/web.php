<?php


use Illuminate\Support\Facades\Route;

Route::middleware('admin')->group(function(){
    Route::get('/', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/logout', [\App\Http\Controllers\AuthController::class, 'do_logout'])->name('do_logout');
    Route::post('/token', [\App\Http\Controllers\AuthController::class, 'generate_token'])->name('generate_token');

    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'index'])->name('profile');
    Route::patch('/profile', [\App\Http\Controllers\ProfileController::class, 'do_update'])->name('profile.do_update');

    Route::prefix('/forms')->group(function(){
        Route::get('/', [\App\Http\Controllers\FormController::class, 'index'])->name('forms');
        Route::get('/create', [\App\Http\Controllers\FormController::class, 'create'])->name('forms.create');

        Route::post('/create', [\App\Http\Controllers\FormController::class, 'do_create'])->name('forms.do_create');

        Route::get('/data/{id}', [\App\Http\Controllers\FormController::class, 'form_data'])->name('forms.form_data');
        Route::get('/data/create/{id}', [\App\Http\Controllers\FormController::class, 'create_data'])->name('forms.create_data');
        Route::get('/data/update/{id}/{formData}', [\App\Http\Controllers\FormController::class, 'update_data'])->name('forms.update_data');
        Route::get('/data/{id}/{formData}', [\App\Http\Controllers\FormController::class, 'show_data'])->name('forms.show_data');
        Route::post('/data/create/{form}', [\App\Http\Controllers\FormController::class, 'do_create_data'])->name('forms.do_create_data');
        Route::patch('/data/create/{formData}', [\App\Http\Controllers\FormController::class, 'do_update_data'])->name('forms.do_update_data');
        Route::post('/data/delete/{formData}', [\App\Http\Controllers\FormController::class, 'do_delete_data'])->name('forms.do_delete_data');

        Route::get('/{form}', [\App\Http\Controllers\FormController::class, 'update'])->name('forms.update');
        Route::patch('/{form}', [\App\Http\Controllers\FormController::class, 'do_update'])->name('forms.do_update');
        Route::post('/delete/{form}', [\App\Http\Controllers\FormController::class, 'do_delete'])->name('forms.do_delete');

        Route::post('/fields/{form}', [App\Http\Controllers\FormController::class, 'do_add_field'])->name('forms.do_add_field');
        Route::post('/fields/{form}/{field}', [App\Http\Controllers\FormController::class, 'do_remove_field'])->name('forms.do_remove_field');
    });

    Route::prefix('/fields')->group(function(){
        Route::get('/', [\App\Http\Controllers\FieldController::class, 'index'])->name('fields');
        Route::get('/create', [\App\Http\Controllers\FieldController::class, 'create'])->name('fields.create');
        Route::post('/create', [\App\Http\Controllers\FieldController::class, 'do_create'])->name('fields.do_create');
        Route::get('/{field}', [\App\Http\Controllers\FieldController::class, 'update'])->name('fields.update');
        Route::patch('/{field}', [\App\Http\Controllers\FieldController::class, 'do_update'])->name('fields.do_update');
        Route::post('/delete/{field}', [\App\Http\Controllers\FieldController::class, 'do_delete'])->name('fields.do_delete');
    });
});

Route::middleware('super')->group(function(){

    Route::prefix('/users')->group(function(){
        Route::get('/', [\App\Http\Controllers\UserController::class, 'index'])->name('users');
        Route::get('/create', [\App\Http\Controllers\UserController::class, 'create'])->name('users.create');
        Route::post('/create', [\App\Http\Controllers\UserController::class, 'do_create'])->name('users.do_create');
        Route::get('/{user}', [\App\Http\Controllers\UserController::class, 'update'])->name('users.update');
        Route::patch('/{user}', [\App\Http\Controllers\UserController::class, 'do_update'])->name('users.do_update');
        Route::post('/delete/{user}', [\App\Http\Controllers\UserController::class, 'do_delete'])->name('users.do_delete');
    });
    
});

Route::get('/login', [\App\Http\Controllers\AuthController::class, 'login'])->name('login');
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'do_login'])->name('do_login');



Route::middleware(['cors', 'APIAuth'])->group(function(){
    Route::get('/api/forms/{formData}', [\App\Http\Controllers\FormController::class, 'show_api_data'])->name('forms.api.show');
    Route::post('/api/forms/{form}', [\App\Http\Controllers\FormController::class, 'do_create_api_data'])->name('forms.api.create');
    Route::patch('/api/forms/{form}/{formData}', [\App\Http\Controllers\FormController::class, 'do_update_api_data'])->name('forms.api.update');
});
