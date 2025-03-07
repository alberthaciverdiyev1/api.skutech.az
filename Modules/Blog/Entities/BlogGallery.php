<?php

namespace Modules\Blog\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Blog\Database\Factories\BlogGalleryFactory;
use Modules\Blog\Traits\BlogGallery\BlogGalleryMethods;
use Modules\Blog\Traits\BlogGallery\BlogGalleryRelations;
use Modules\Blog\Traits\BlogGallery\BlogGalleryScopes;
use Modules\Core\Traits\Data\HasImagePreview;

// use Modules\Blog\Database\Factories\BlogGalleryFactory;

class BlogGallery extends Model
{
    use HasFactory;
    use HasImagePreview;
    use BlogGalleryScopes;
    use BlogGalleryMethods;
    use BlogGalleryRelations;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'blog_id',
        'image'
    ];

    protected static function newFactory(): BlogGalleryFactory
    {
        return BlogGalleryFactory::new();
    }
}
