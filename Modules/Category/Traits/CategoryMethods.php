<?php

namespace Modules\Category\Traits;

use Illuminate\Support\Collection;
use Modules\Product\Models\Product;

trait CategoryMethods
{
    public function getAllChildCategories()
    {
        $childCategories = collect();

        foreach ($this->children as $child) {
            $childCategories->push($child);
            $childCategories = $childCategories->merge($child->getAllChildCategories());
        }

        return $childCategories;
    }

    public function fullSlug(): string
    {
        if ($this->parent) {
            return $this->parent->fullSlug() . '/' . $this->slug();
        }

        return $this->slug();
    }

    public function getAllChildrenIds($category): array
    {
        $childIds = [$category->id];

        foreach ($category->children as $child) {
            $childIds[] = $child->id;
            $childIds = array_merge($childIds, $this->getAllChildrenIds($child));
        }

        return $childIds;
    }

    public function ancestors(): Collection
    {
        $ancestors = collect([$this]);

        $parent = $this->parent;

        while ($parent) {
            $ancestors->prepend($parent);
            $parent = $parent->parent;
        }

        return $ancestors;
    }

    public function getAllProductsCount(): int
    {
        $categoryIds = $this->getAllChildrenIds($this);

        return Product::active()->whereIn('category_id', $categoryIds)->count();
    }
}
