<?php

namespace Jiannius\Atom\Traits\Livewire;

trait WithShareable
{
    public $shareable;

    /**
     * Mount
     */
    public function mountWithShareable()
    {
        if ($this->shareable && !is_array($this->shareable)) {
            $this->shareable = $this->shareable->toArray();
        }
    }

    /**
     * Regenerate shareable
     */
    public function regenerateShareable(): void
    {
        $shareable = model('shareable')->find(data_get($this->shareable, 'id'));
        $shareable->fill(['uuid' => null])->save();

        $this->shareable = $shareable->toArray();
    }

    /**
     * Updated shareable
     */
    public function updatedShareable($val, $attr)
    {
        $shareable = model('shareable')->find(data_get($this->shareable, 'id'));
        $shareable->fill([
            $attr => $attr === 'valid_for' && !is_numeric($val) ? null : $val,
        ])->save();

        $this->shareable = $shareable->toArray();
    }
}