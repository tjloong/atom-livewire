<?php

namespace Jiannius\Atom\Traits\Livewire;

trait WithTable
{
    public $maxRows = 100;
    public $checkboxes = [];
    
    /**
     * Toggle checkbox
     */
    public function toggleCheckbox($value)
    {
        $values = collect($this->checkboxes);

        if (in_array($value, ['*', '**'])) {
            if (in_array($values->first(), ['*', '**'])) $values = collect();
            else $values = collect([$value]);
        }
        else {
            $values = $values->reject('*')->reject('**');
            $values = $values->contains($value)
                ? $values->reject($value)
                : $values->concat([$value]);
        }

        $this->fill(['checkboxes' => $values->values()->all()]);
    }

    /**
     * Reset table checkboxes
     */
    public function resetCheckboxes()
    {
        $this->checkboxes = [];
    }
}