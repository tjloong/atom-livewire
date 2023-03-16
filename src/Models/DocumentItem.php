<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentItem extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'qty' => 'float',
        'amount' => 'float',
        'subtotal' => 'float',
        'seq' => 'integer',
        'product_id' => 'integer',
        'product_variant_id' => 'integer',
        'document_id' => 'integer',
    ];

    /**
     * Model boot
     */
    protected static function boot(): void
    {
        parent::boot();

        static::saving(function($item) {
            $item->name = $item->name ?? 'Unknown Item';
        });

        static::updated(function($item) {
            $item->document->setSummary();
        });
    }

    /**
     * Get product for document item
     */
    public function product(): BelongsTo
    {
        if (!enabled_module('products')) return null;

        return $this->belongsTo(model('product'));
    }

    /**
     * Get product variant for document item
     */
    public function productVariant(): BelongsTo
    {
        if (!enabled_module('products')) return null;

        return $this->belongsTo(model('product_variant'));
    }

    /**
     * Get document for document item
     */
    public function document(): BelongsTo
    {
        return $this->belongsTo(model('document'));
    }

    /**
     * Get taxes for document item
     */
    public function taxes()
    {
        if (!enabled_module('taxes')) return null;
        
        return $this->belongsToMany(model('tax'), 'document_item_taxes')->withPivot('amount');
    }
}
