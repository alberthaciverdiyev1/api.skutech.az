<?php

namespace Modules\Core\Traits\Data;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Modules\Core\Enums\FileStorage;
use Modules\Core\Traits\Files\ImageCompressor;

trait HasBackground
{
    use ImageCompressor;
    use HasBackgroundPreview;

    /**
     * @return void
     * @throws Exception
     */
    protected static function bootHasBackground(): void
    {
        /**
         * @param Model $model
         * @return void
         * @throws Exception
         */
        $processImage = function (Model $model) {
            $requestImage = request()->hasFile(self::backgroundFrom());

            if ($requestImage) {
                self::removeExistBackground($model);
                $imagePath = self::compressImage(request()->file(self::backgroundFrom()), Str::random(10));
            } else {
                $imagePath = $model->getAttributeValue(self::backgroundFrom());
            }

            $model->setAttribute(self::backgroundFrom(), $imagePath);
        };

        static::creating($processImage);
        static::updating($processImage);

        static::deleting(function (Model $model) {
            self::removeExistBackground($model);
        });
    }

    /**
     * @param $model
     * @return void
     */
    private static function removeExistBackground($model): void
    {
        $originalImage = $model->getOriginal(self::backgroundFrom());

        if ($originalImage && Storage::disk(FileStorage::PUBLIC)->exists('images/' . $originalImage)) {
            Storage::disk(FileStorage::PUBLIC)->delete('images/' . $originalImage);
        }
    }
}
