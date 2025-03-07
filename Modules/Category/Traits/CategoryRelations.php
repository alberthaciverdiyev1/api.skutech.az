<?php

namespace Modules\Category\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Facades\DB;
use Modules\AdditionalFee\Models\AdditionalFee;
use Modules\Category\Models\Category;
use Modules\Discount\Models\Discount;
use Modules\Filter\Models\CategoryFilter;
use Modules\Filter\Models\Filter;
use Modules\Product\Models\Product;

trait CategoryRelations
{
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id')->with('parent');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')->with('children');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function filters(): BelongsToMany
    {
        return $this->belongsToMany(Filter::class, 'category_filters')->with('options')->withTimestamps();
    }

    public function discounts()
    {
        return $this->morphToMany(Discount::class, 'discountable');
    }

    public function additionalFees()
    {
        return $this->morphToMany(AdditionalFee::class, 'feeable');
    }
}
