<?php

namespace Jiannius\Atom\Traits\Models;

trait HasShareable
{
    public $enabledHasShareableTrait = true;

    /**
     * Boot the trait
     *
     * @return void
     */
    protected static function bootHasShareable()
    {
        //
    }

    /**
     * Initialize the trait
     * 
     * @return void
     */
    protected function initializeHasShareable()
    {
        $this->casts['shareable_id'] = 'integer';
    }

    /**
     * Get shareable for model
     */
    public function shareable()
    {
        return $this->belongsTo(get_class(model('shareable')));
    }
}