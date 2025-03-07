<?php

namespace Modules\Category\Entities;

use App\Traits\Data\HasSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Category\Traits\CategoryMethods;
use Modules\Category\Traits\CategoryRelations;
use Modules\Category\Traits\CategoryScopes;
use Modules\Core\Traits\Data\HasIcon;
use Spatie\Translatable\HasTranslations;

// use Modules\Category\Database\Factories\CategoryFactory;

class Category extends Model
{
    use HasFactory;
    use HasTranslations;
    use HasSlug;
    use HasIcon;
    use CategoryScopes;
    use CategoryMethods;
    use CategoryRelations;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'parent_id',
        'slug',
        'title',
        'description',
        'icon',
        'background',
        'order',
        'active'
    ];

    public array $translatable = ['title', 'description'];

    protected static function newFactory(): CategoryFactory
    {
        return CategoryFactory::new();
    }
}
