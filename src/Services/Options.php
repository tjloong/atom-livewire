<?php

namespace Jiannius\Atom\Services;

class Options
{
    public $filters;
    public $selected = [];
    public $options = [];

    public function filter($filters)
    {
        $filters = collect($filters);
        $selected = $filters->pull('value');

        $this->filters = $filters;
        $this->selected = (array) $selected;

        return $this;
    }
}