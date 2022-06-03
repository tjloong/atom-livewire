<?php

namespace Jiannius\Atom\Traits;

trait WithTableCheckbox
{
    public $checkedTableRows = [];

    public function getListeners()
    {
        return $this->listeners + [
            'table-checkbox-check' => 'tableCheckboxCheck',
        ];
    }

    public function tableCheckboxCheck($data)
    {
        $name = data_get($data, 'name');
        $value = data_get($data, 'value');
        $checked = collect(data_get($this->checkedTableRows, $name, []));

        if ($checked->contains($value)) $checked = $checked->reject(fn($val) => $val === $value);
        else if (in_array($value, ['all', 'everything'])) $checked = collect([$value]);
        else $checked->push($value);

        if ($checked->count() > 1 && in_array($checked->first(), ['all', 'everything'])) $checked->shift();

        $this->checkedTableRows[$name] = $checked;
        $this->dispatchBrowserEvent('table-checkbox-checked', $this->getCheckedTableRows($name));
    }

    public function getCheckedTableRows($name = null)
    {
        if ($name) return $this->checkedTableRows[$name];
        else {
            $keys = array_keys($this->checkedTableRows);
    
            if (count($keys) === 1) return $this->checkedTableRows[head($keys)];
    
            return $this->checkedTableRows;
        }
    }

    public function resetTableCheckbox()
    {
        $this->checkedTableRows = [];
        $this->dispatchBrowserEvent('table-checkbox-checked', []);
    }
}