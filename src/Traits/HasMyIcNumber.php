<?php

namespace Jiannius\Atom\Traits;

trait HasMyIcNumber
{
    /**
     * Scope for my ic number
     */
    public function scopeMyIcNumber($query, $ic)
    {
        if (str($ic)->is('*-*-*')) {
            $dashed = $ic;
            $nodashed = str($ic)->replace('-', '')->toString();
        }
        else {
            $head = substr($ic, 0, 6);
            $body = substr($ic, 6, 2);
            $tail = substr($ic, 8);
            $dashed = implode('-', [$head, $body, $tail]);
            $nodashed = $ic;
        }

        return $query->whereIn('ic', [$dashed, $nodashed]);
    }
}