<?php

namespace App\Traits\Files;

use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\Drivers\Imagick\Driver as ImagickDriver;
use Intervention\Image\ImageManager;

trait ImageCompressor
{
    /**
     * Compress and save the uploaded image.
     *
     * @param UploadedFile $image
     * @param string $fileName
     * @return string
     * @throws Exception
     */
    protected static function compressImage(UploadedFile $image, string $fileName): string
    {
        $directory = 'storage/images/';

        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0777, true, true);
        }

        $imageFormat = strtolower(pathinfo($image->getClientOriginalName(), PATHINFO_EXTENSION));
        $driver = config('core.image_driver');
        $compressionPercent = config('core.compression_percent');

        if (!in_array($driver, ['gd', 'imagick'])) {
            throw new Exception("The driver \"$driver\" is not a valid Intervention driver.");
        }

        $imagePath = self::generateImagePath($fileName, $imageFormat);

        // For SVG or WEBP, just save the image without any compression
        if (in_array($imageFormat, ['webp', 'svg'])) {
            $image->move($directory, $imagePath);
        } else {
            self::compressAndSaveImage($image, $directory, $imagePath, $driver, $compressionPercent);
        }

        return $imagePath;
    }

    /**
     * Generate the image path.
     *
     * @param string $fileName
     * @param string $imageFormat
     * @return string
     */
    private static function generateImagePath(string $fileName, string $imageFormat): string
    {
        return $fileName . '-' . Str::uuid() . (in_array($imageFormat, ['webp', 'svg']) ? ".$imageFormat" : '.webp');
    }

    /**
     * Compress and save the image as WEBP format.
     *
     * @param UploadedFile $image
     * @param string $directory
     * @param string $imagePath
     * @param string $driver
     * @param int|null $compressionPercent
     * @return void
     */
    private static function compressAndSaveImage(UploadedFile $image, string $directory, string $imagePath, string $driver, ?int $compressionPercent): void
    {
        $imageManager = $driver === 'gd' ? new ImageManager(new GdDriver()) : new ImageManager(new ImagickDriver());

        $imageResource = $imageManager->read($image->getRealPath());

        $imageResource->toWebp($compressionPercent ?? 50)
            ->save($directory . $imagePath);
    }
}
