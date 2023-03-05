<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    protected static function boot()
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
    public function product()
    {
        return $this->belongsTo(get_class(model('product')));
    }

    /**
     * Get product variant for document item
     */
    public function productVariant()
    {
        return $this->belongsTo(get_class(model('product_variant')));
    }

    /**
     * Get document for document item
     */
    public function document()
    {
        return $this->belongsTo(get_class(model('document')));
    }

    /**
     * Get taxes for document item
     */
    public function taxes()
    {
        if (!enabled_module('taxes')) return;
        
        return $this->belongsToMany(get_class(model('tax')), 'document_item_taxes')->withPivot('amount');
    }
}
