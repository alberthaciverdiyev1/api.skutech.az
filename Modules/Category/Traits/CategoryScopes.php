<?php

namespace Modules\Category\Traits;

use Modules\Core\Traits\Scopes\HasActiveScope;

trait CategoryScopes
{
    use HasActiveScope;

    public function scopeMain($query)
    {
        return $query->whereNull('parent_id')->ordered();
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}
