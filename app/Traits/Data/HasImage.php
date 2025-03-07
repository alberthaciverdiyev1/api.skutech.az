<?php

namespace App\Traits\Data;

use Illuminate\Support\Facades\Vite;

trait HasImage
{
    /**
     * @return string
     */
    public static function imageFrom(): string
    {
        return 'image';
    }

    /**
     * @return string|null
     */
    public function getImage(): ?string
    {
        return $this->getAttribute(self::imageFrom())
            ? filter_var($this->getAttribute(self::imageFrom()), FILTER_VALIDATE_URL)
                ? $this->getAttribute(self::imageFrom())
                : asset('/storage/images/' . $this->getAttribute(self::imageFrom()))
            : Vite::dashboardImage('placeholders/no-image.png');
    }
}
