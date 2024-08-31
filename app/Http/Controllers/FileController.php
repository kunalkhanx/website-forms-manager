<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;

class FileController extends Controller
{
    public function download(File $file){
        if(!$file){
            return response('', 404);
        }

        $filePath = storage_path('app/' . $file->path);

        if (!file_exists($filePath)) {
            return response('', 404);
        }

        $fileName = basename($file->path);

        return response()->download($filePath, $fileName);
    }
}
