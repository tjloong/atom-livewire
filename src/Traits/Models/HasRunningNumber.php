<?php

namespace Jiannius\Atom\Traits\Models;

use Illuminate\Support\Facades\DB;

trait HasRunningNumber
{
    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function bootHasRunningNumber()
    {
        // listen to creating event
        static::saving(function ($model) {
            if (!$model->number) {
                $duplicated = true;
                $table = $model->getTable();
                $prefix = $model->getRunningNumberPrefix();
                $last = optional(DB::table($table)->latest()->first())->number;
                $n = $last
                    ? (integer) str($last)->replaceFirst($prefix.'-', '')->toString()
                    : 0;

                while ($duplicated) {
                    $n = $n + 1;
                    $postfix = str()->padLeft($n, 6, '0');
                    $number = collect([$prefix, $postfix])->filter()->join('-');
                    $duplicated = DB::table($table)->where('number', $number)->count() > 0;
                }

                $model->number = $number;
            }
        });
    }

    /**
     * Get unique number prefix
     * 
     * @return string
     */
    protected function getRunningNumberPrefix()
    {
        if (method_exists($this, 'runningNumberPrefix')) return $this->runningNumberPrefix();
        else return $this->runningNumberPrefix ?? null;
    }
}