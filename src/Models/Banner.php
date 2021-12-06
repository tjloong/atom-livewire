<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
        'name',
        'type',
        'url',
        'is_active',
        'start_at',
        'end_at',
        'image_id',
    ];

    protected $casts = [
        'start_at' => 'date',
        'end_at' => 'date',
        'image_id' => 'integer',
    ];

    /**
     * Scope for fussy search
     * 
     * @param Builder $query
     * @param string $search
     * @return Builder
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(fn($q) => $q
            ->where('name', 'like', "%$search%")
            ->orWhere('type', 'like', "%$search%")
            ->orWhere('url', 'like', "%$search%")
        );
    }

    /**
     * Scope for status
     * 
     * @param Builder $query
     * @param string $status
     * @return Builder
     */
    public function scopeStatus($query, $status)
    {
        return $query
            ->when($status === 'pending', fn($q) => $q->whereDate('start_at', '>', today()))
            ->when($status === 'ended', fn($q) => $q->whereDate('end_at', '<=', today()))
            ->when($status === 'inactive', fn($q) => $q->where('is_active', false))
            ->when($status === 'active', fn($q) => $q->where(fn($q) => $q
                ->whereRaw('(start_at is null and end_at is null)')
                ->orWhereRaw('(start_at is not null and end_at is not null and start_at <= curdate() and end_at > curdate())')
                ->orWhereRaw('(start_at is not null and end_at is null and start_at <= curdate())')
                ->orWhereRaw('(start_at is null and end_at is not null and end_at > curdate())')
            ));
    }

    /**
     * Get status attribute
     * 
     * @return string
     */
    public function getStatusAttribute()
    {
        if ($this->start_at && $this->start_at->startOfDay()->isFuture()) return 'pending';
        if ($this->end_at && $this->end_at->endOfDay()->isPast()) return 'ended';
        if (!$this->is_active) return 'inactive';

        return 'active';
    }
}
