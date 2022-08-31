<?php

namespace Jiannius\Atom\Models;

use Jiannius\Atom\Traits\HasFilters;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFilters;
    
    protected $guarded = [];

    protected $casts = [
        'price' => 'float',
        'stock' => 'integer',
        'seq' => 'integer',
        'is_default' => 'boolean',
        'is_active' => 'boolean',
        'image_id' => 'integer',
        'product_id' => 'integer',
    ];
    
    /**
     * Get product for product variant
     */
    public function product()
    {
        return $this->belongsTo(get_class(model('product')));
    }

    /**
     * Get image for product variant
     */
    public function image()
    {
        return $this->belongsTo(get_class(model('file')), 'image_id');
    }
}
