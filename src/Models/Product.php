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
    ];

    /**
     * Get taxes for product
     */
    public function taxes()
    {
        return $this->belongsToMany(get_class(model('tax')), 'product_taxes');
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
     * Get types
     */
    public function getTypes()
    {
        return [
            ['value' => 'normal', 'label' => 'Normal', 'description' => 'Single item product, eg. can drink, book, phone.'],
            ['value' => 'variant', 'label' => 'With multiple variants', 'description' => 'Product with multiple options, eg. shirt with multiple sizes'],
        ];
    }
}
