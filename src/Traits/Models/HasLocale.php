<?php

namespace Jiannius\Atom\Traits\Models;

trait HasLocale
{
    public $enabledHasLocaleTrait = true;

    /**
     * Get the column value in current locale
     */
    public function locale($column)
    {
        $col = data_get($this, $column);
        $keys = array_keys((array)$col);

        if (is_object($col) || is_array($col)) {
            return data_get($col, app()->currentLocale())
                ?? data_get($col, head($keys));
        }
        else return $col;
    }
}