<?php

namespace Modules\Core\Traits\Data;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Modules\Core\Enums\FileStorage;
use Modules\Core\Traits\Files\ImageCompressor;

trait HasIcon
{
    use ImageCompressor;
    use HasIconPreview;

    /**
     * @return void
     * @throws Exception
     */
    protected static function bootHasIcon(): void
    {
        /**
         * @param Model $model
         * @return void
         * @throws Exception
         */
        $processImage = function (Model $model) {
            $requestImage = request()->hasFile(self::iconFrom());

            if ($requestImage) {
                self::removeExistIcon($model);
                $imagePath = self::compressImage(request()->file(self::iconFrom()), Str::random(10));
            } else {
                $imagePath = $model->getAttributeValue(self::iconFrom());
            }

            $model->setAttribute(self::iconFrom(), $imagePath);
        };

        static::creating($processImage);
        static::updating($processImage);

        static::deleting(function (Model $model) {
            self::removeExistIcon($model);
        });
    }

    /**
     * @param $model
     * @return void
     */
    private static function removeExistIcon($model): void
    {
        $originalImage = $model->getOriginal(self::iconFrom());

        if ($originalImage && Storage::disk(FileStorage::PUBLIC)->exists('images/' . $originalImage)) {
            Storage::disk(FileStorage::PUBLIC)->delete('images/' . $originalImage);
        }
    }
}
