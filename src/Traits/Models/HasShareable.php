<?php

namespace Jiannius\Atom\Traits\Models;

use Illuminate\Database\Eloquent\Relations\HasOne;

trait HasShareable
{
    public $enabledHasShareableTrait = true;

    /**
     * Boot the trait
     */
    protected static function bootHasShareable(): void
    {
        static::saved(function($model) {
            if (!$model->shareable) {
                $model->shareable()->create(['is_enabled' => false]);
            }
        });
    }

    /**
     * Initialize the trait
     */
    protected function initializeHasShareable(): void
    {
        $this->casts['shareable_id'] = 'integer';
    }

    /**
     * Get shareable for model
     */
    public function shareable(): HasOne
    {
        return $this->hasOne(model('shareable'));
    }
}