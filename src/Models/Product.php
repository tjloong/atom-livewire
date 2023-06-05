<?php

namespace Jiannius\Atom\Models;

use Jiannius\Atom\Traits\Models\HasTrace;
use Jiannius\Atom\Traits\Models\HasFilters;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Jiannius\Atom\Traits\Models\HasSlug;

class Product extends Model
{
    use HasFilters;
    use HasSlug;
    use HasTrace;
    
    protected $guarded = [];

    protected $casts = [
        'price' => 'float',
        'cost' => 'float',
        'stock' => 'integer',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    /**
     * Model booted
     */
    protected static function booted(): void
    {
        static::saving(function ($product) {
            $product->code = $product->code ?? $product->generateCode();
        });
    }

    /**
	 * Get images for product
	 */
	public function images(): BelongsToMany
	{
		return $this->belongsToMany(model('file'), 'product_images')->withPivot('seq');
	}

    /**
     * Get product categories for product
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(model('label'), 'product_categories');
    }

    /**
     * Get product variants for product
     */
    public function variants(): mixed
    {
        if (!has_table('product_variants')) return null;

        return $this->hasMany(model('product_variant'));
    }

    /**
     * Get coupons for product
     */
    public function coupons(): mixed
    {
        if (!has_table('coupon_products')) return null;

        return $this->belongsToMany(model('coupon'), 'coupon_products');
    }

    /**
     * Get taxes for product
     */
    public function taxes(): mixed
    {
        if (!has_table('product_taxes')) return null;

        return $this->belongsToMany(model('tax'), 'product_taxes');
    }

    /**
     * Scope for search
     */
    public function scopeSearch($query, $search): void
    {
        $query->where(fn($q) => $q
            ->where('code', $search)
            ->orWhere('name', 'like', "%$search%")
            ->orWhere('description', 'like', "%$search%")
        );
    }

    /**
     * Get types
     */
    public function getTypes(): array
    {
        return [
            ['value' => 'normal', 'label' => 'Normal', 'description' => 'Single item product, eg. can drink, book, phone.'],
            ['value' => 'variant', 'label' => 'With multiple variants', 'description' => 'Product with multiple options, eg. shirt with multiple sizes'],
        ];
    }

    /**
     * Generate code
     */
    public function generateCode(): string
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
