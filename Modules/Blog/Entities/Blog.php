<?php

namespace Modules\Blog\Entities;

use App\Traits\Data\HasImage;
use App\Traits\Data\HasSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations;

// use Modules\Blog\Database\Factories\BlogFactory;

class Blog extends Model
{
    use HasFactory;
//    use HasTranslations;
    use HasSlug;
    use HasImage;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'slug',
        'title',
        'description',
        'image',
        'views',
        'active',
    ];

    public array $translatable = ['title', 'description'];

    public function gallery()
    {
        return $this->hasMany(BlogGallery::class);
    }
    protected static function newFactory(): BlogFactory
    {
        return BlogFactory::new();
    }
}
