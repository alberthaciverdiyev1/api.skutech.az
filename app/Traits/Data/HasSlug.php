<?php

namespace App\Traits\Data;

use Illuminate\Database\Eloquent\Model;


trait HasSlug
{
    protected static int $i = 1;

    protected static function bootHasSlug(): void
    {
        static::creating(function (Model $model) {
            $slug = str()->slug(self::generateSlug($model, str($model->{self::slugFrom()})->slug()));
            $model->setAttribute('slug', $slug);
        });

        static::updating(function (Model $model) {
            $slug = str()->slug(self::generateSlug($model, str()->slug($model->{self::slugFrom()})));
            $model->setAttribute('slug', $slug);
        });
    }

    public static function generateSlug($model, $slug)
    {
        $originalSlug = $slug;

        while ($model::query()->where('slug', $slug)->where('id','!=',$model->getAttribute('id'))->exists()) {
            $slug = $originalSlug . '-' . ++self::$i;
        }

        return $slug;
    }

    public static function slugFrom(): string
    {
        return 'title';
    }

    public function slug(): ?string
    {
        return $this->getAttribute('slug');
    }
}
