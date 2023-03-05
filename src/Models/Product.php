<?php

namespace Jiannius\Atom\Models;

use Jiannius\Atom\Traits\Models\HasTrace;
use Jiannius\Atom\Traits\Models\HasFilters;
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
        if (!enabled_module('taxes')) return;
        
        return $this->belongsToMany(model('tax'), 'product_taxes');
    }

    /**
	 * Get images for product
	 */
	public function images()
	{
		return $this->belongsToMany(model('file'), 'product_images')->withPivot('seq');
	}

    /**
     * Get product categories for product
     */
    public function categories()
    {
        return $this->belongsToMany(model('label'), 'product_categories');
    }

    /**
     * Get product variants for product
     */
    public function variants()
    {
        return $this->hasMany(model('product_variant'));
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
     * Get types
     */
    public function getTypes()
    {
        return [
            ['value' => 'normal', 'label' => 'Normal', 'description' => 'Single item product, eg. can drink, book, phone.'],
            ['value' => 'variant', 'label' => 'With multiple variants', 'description' => 'Product with multiple options, eg. shirt with multiple sizes'],
        ];
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
                ->readable()
                ->where('code', $code)
                ->count() > 0;
        }

        return $code;
    }
}
