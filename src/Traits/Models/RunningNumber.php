<?php

namespace Jiannius\Atom\Traits\Models;

use Illuminate\Support\Facades\DB;

trait RunningNumber
{
    protected static function bootRunningNumber()
    {
        static::saving(function ($model) {
            if ($model->number === 'temp') {
                $model->number = collect(['TEMP', str()->random(), time()])->join('-');
            }
            else if (!$model->number) {
                $dup = true;
                $table = $model->getTable();
                $prefix = $model->runningNumberPrefix();

                $last = optional(
                    DB::table($table)
                        ->where('number', 'not like', "TEMP-%")
                        ->where('id', '<>', $model->id)
                        ->latest()
                        ->first()
                )->number;

                $n = $last
                    ? (integer) str($last)->replaceFirst($prefix.'-', '')->toString()
                    : 0;

                while ($dup) {
                    $n = $n + 1;
                    $postfix = str()->padLeft($n, 6, '0');
                    $number = collect([$prefix, $postfix])->filter()->join('-');
                    $dup = DB::table($table)->where('number', $number)->count() > 0;
                }

                $model->number = $number;
            }
        });
    }

    public function runningNumberPrefix()
    {
        return '';
    }

    public function scopeTempNumber($query)
    {
        $query->where('number', 'like', 'TEMP-%');
    }
}