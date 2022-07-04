<?php

namespace Jiannius\Atom\Models;

use Jiannius\Atom\Traits\HasTrace;
use Jiannius\Atom\Traits\HasFilters;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasTrace;
    use HasFilters;
    
    protected $guarded = [];

    protected $casts = [
        'price' => 'float',
        'stock' => 'integer',
        'is_active' => 'boolean',
        'tax_id' => 'integer',
    ];

    protected $appends = ['tax_amount'];

    /**
     * Model boot
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function($product) {
            if (empty($product->code)) $product->code = $product->generateCode();
        });
    }

    /**
     * Get tax for product
     */
    public function tax()
    {
        return $this->belongsTo(get_class(model('tax')));
    }

    /**
	 * Get images for product
	 */
	public function productImages()
	{
		return $this->belongsToMany(get_class(model('file')), 'product_images')->withPivot('seq');
	}

    /**
     * Get product categories for product
     */
    public function productCategories()
    {
        return $this->belongsToMany(get_class(model('label')), 'product_categories');
    }

    /**
     * Get product variants for product
     */
    public function productVariants()
    {
        return $this->hasMany(get_class(model('product_variant')));
    }

    /**
     * Scope for search
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(fn($q) => $q
            ->where('code', $search)
            ->orWhere('name', 'like', "%$search%")
            ->orWhere('description', 'like', "%$search%")
        );
    }

    /**
     * Scope for product category
     */
    public function scopeProductCategory($query, $categories)
    {
        return $query->whereHas('productCategories', fn($q) => $q->whereIn('labels.id', (array)$categories));
    }

    /**
     * Get tax amount attribute
     */
    public function getTaxAmountAttribute()
    {
        return optional($this->tax)->calculate($this->price);
    }

    /**
     * Get types
     */
    public function getTypes()
    {
        return collect(['normal', 'variant']);
    }

    /**
     * Generate code
     */
    public function generateCode()
    {
        $code = null;
        $dup = true;

        while ($dup) {
            $code = str()->upper(str()->random(6));
            $dup = model('product')
                ->belongsToAccount()
                ->where('code', $code)
                ->count() > 0;
        }

        return $code;
    }
}
