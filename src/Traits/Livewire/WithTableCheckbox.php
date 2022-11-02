<?php

namespace Jiannius\Atom\Traits\Livewire;

trait WithTableCheckbox
{
    public $checkboxes = [];
    
    /**
     * Toggle checkbox
     */
    public function toggleCheckbox($data)
    {
        $name = data_get($data, 'name');
        $value = data_get($data, 'value');
        $cbs = collect($this->checkboxes)->where('name', data_get($data, 'name'))->map('collect');

        if (in_array($value, ['*', '**'])) {
            $this->checkboxes = in_array(optional($cbs->first())->get('value'), ['*', '**'])
                ? [] : [$data];
        }
        else {
            $cbs = $cbs->reject(fn($cb) => in_array($cb->get('value'), ['*', '**']));
            $exists = $cbs->where('value', $value)->count() > 0;
    
            $this->checkboxes = (
                $exists
                    ? $cbs->where('value', '!==', $value)
                    : $cbs->concat([$data])
            )->values()->all();
        }

        $this->dispatchBrowserEvent('table-checkboxes-changed', $this->getTableCheckboxes($name));
    }

    /**
     * Get table checkboxes
     */
    public function getTableCheckboxes($name = 'table')
    {
        return collect($this->checkboxes)
            ->where('name', $name)
            ->pluck('value')
            ->values()
            ->all();
    }

    /**
     * Reset table checkboxes
     */
    public function resetTableCheckboxes($name = 'table')
    {
        $this->checkboxes = collect($this->checkboxes)
            ->reject(fn($val) => data_get($val, 'name') === $name)
            ->values()
            ->all();

        $this->dispatchBrowserEvent('table-checkboxes-changed', $this->checkboxes);
    }
}