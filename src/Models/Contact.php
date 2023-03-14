<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Jiannius\Atom\Traits\Models\HasFilters;
use Jiannius\Atom\Traits\Models\HasTrace;

class Contact extends Model
{
    use HasFactory;
    use HasFilters;
    use HasTrace;

    protected $guarded = [];

    protected $casts = [
        'data' => 'object',
        'avatar_id' => 'integer',
    ];

    /**
     * Attribute for email phone
     */
    public function emailPhone(): Attribute
    {
        return new Attribute(
            get: fn () => collect([$this->email, $this->phone])->filter()->join(' | '),
        );
    }

    /**
     * Get avatar for contact
     */
    public function avatar(): BelongsTo
    {
        return $this->belongsTo(get_class(model('file')));
    }

    /**
     * Get persons for contact
     */
    public function persons(): HasMany
    {
        return $this->hasMany(get_class(model('contact_person')));
    }

    /**
     * Scope for search
     */
    public function scopeSearch($query, $search): void
    {
        $query->where(fn($q) => $q
            ->where('name', 'like', "%$search%")
            ->orWhere('email', 'like', "%$search%")
            ->orWhere('phone', 'like', "%$search%")
            ->orWhereHas('persons', fn($q) => $q->search($search))
        );
    }
}
