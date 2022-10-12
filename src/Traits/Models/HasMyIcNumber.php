<?php

namespace Jiannius\Atom\Traits\Models;

trait HasMyIcNumber
{
    /**
     * Scope for my ic number
     */
    public function scopeMyIcNumber($query, $ic)
    {
        $dashed = $this->formatIc($ic);
        $nodashed = $this->formatIc($ic, false);

        return $query->whereIn('ic', [$dashed, $nodashed]);
    }

    /**
     * Get ic number
     */
    public function getIcAttribute($value)
    {
        return $this->formatIc($value);
    }

    /**
     * Format ic
     */
    public function formatIc($value, $dashed = true)
    {
        if ($dashed) {
            if (str($value)->is('*-*-*')) return $value;
            else {
                $head = substr($value, 0, 6);
                $body = substr($value, 6, 2);
                $tail = substr($value, 8);

                return implode('-', [$head, $body, $tail]);    
            }
        }
        else {
            if (str($value)->is('*-*-*')) return str($value)->replace('-', '')->toString();
            else return $value;
        }
    }
}