<?php

namespace Jiannius\Atom\Models;

use Jiannius\Atom\Traits\Models\HasSlug;
use Jiannius\Atom\Traits\Models\HasTrace;
use Jiannius\Atom\Traits\Models\HasFilters;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Banner extends Model
{
    use HasSlug;
    use HasTrace;
    use HasFilters;

    protected $guarded = [];

    protected $casts = [
        'placement' => 'array',
        'seq' => 'integer',
        'is_active' => 'boolean',
        'image_id' => 'integer',
    ];

    /**
     * Get image for banner
     */
    public function image(): BelongsTo
    {
        return $this->belongsTo(model('file'), 'image_id');
    }

    /**
     * Scope for fussy search
     */
    public function scopeSearch($query, $search): void
    {
        $query->where(fn($q) => $q
            ->where('name', 'like', "%$search%")
            ->orWhere('description', 'like', "%$search%")
            ->orWhere('url', 'like', "%$search%")
        );
    }
}
