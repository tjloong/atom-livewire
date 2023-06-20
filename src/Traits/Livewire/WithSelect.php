<?php

namespace Jiannius\Atom\Traits\Livewire;

trait WithSelect
{
    public $selectInputSearch;

    // initialize
    public function initializeWithSelect()
    {
        $this->listeners = array_merge($this->listeners ?? [], [
            'setSelectInputSearch',
        ]);
    }

    // Set select input search
    public function setSelectInputSearch($input)
    {
        if (!$this->selectInputSearch) $this->selectInputSearch = collect();

        $key = $this->selectInputSearch->where('id', data_get($input, 'id'))->keys()->first();

        if (is_numeric($key)) $this->selectInputSearch->put($key, $input);
        else $this->selectInputSearch->push($input);
    }

    // Get select input search
    public function getSelectInputSearch($id = null)
    {
        if ($id) return data_get(optional($this->selectInputSearch)->firstWhere('id', $id), 'search');

        return $this->selectInputSearch;
    }
}