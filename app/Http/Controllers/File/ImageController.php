<?php

namespace App\Http\Controllers\File;

use App\Http\Controllers\Controller;
use App\Http\Services\ImageUpload;

class ImageController extends Controller
{

    public function upload()
    {
        $image = ImageUpload::dateFormatUploadEditor("file");
        echo json_encode(['location' => asset($image)]);
    }
}
