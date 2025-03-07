<?php

namespace App\Traits\Files;

use Exception;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait FileUploader
{
    /**
     * @throws Exception
     */
    public static function uploadFile($file, $fileName): string
    {
        $directory = 'storage/files/';

        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0777, true);
        }

        $imageFormat = strtolower(pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION));

        $filePath = $fileName . '-' . Str::uuid() . '.' . $imageFormat;

        $file->move(public_path($directory), $filePath);

        return $filePath;
    }

}
