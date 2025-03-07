<?php

namespace App\Traits\Files;

use Exception;
use Intervention\Image\ImageManagerStatic as Image;

trait Watermark
{
    public static function applyWatermark($imagePath, $watermarkPath, $position = 'center', $margin = 10): bool|string
    {
        try {
            $image = Image::make($imagePath);
            $watermark = Image::make($watermarkPath);

            // Apply watermark logic, you can refer to the insert_watermark method

            $image->insert($watermark, $position, $margin, $margin);

            // Save the manipulated image
            $image->save($imagePath);

            return true;
        } catch (Exception $e) {
            // Handle the exception or log it
            return $e->getMessage();
        }
    }
}
