<?php

namespace App\Http\Controllers;

use App\Models\File;

class FileController extends Controller
{

    /**
     * Function - Download a file by id
     * 
     * @param File $file
     * 
     * @return Response
     */
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
