<?php

namespace Jiannius\Atom\Traits\Models;

use Illuminate\Support\Facades\DB;

trait HasRunningNumber
{
    // boot
    protected static function bootHasRunningNumber()
    {
        // listen to creating event
        static::saving(function ($model) {
            if ($model->number === 'temp') {
                $model->number = collect(['TEMP', str()->random(), time()])->join('-');
            }
            else if (!$model->number) {
                $dup = true;
                $table = $model->getTable();
                $prefix = $model->getPrefix();

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
                    $dup = DB::table($table)
                        ->where('number', $number)
                        ->count() > 0;
                }

                $model->number = $number;
            }
        });
    }

    // get prefix
    protected function getPrefix()
    {
        return $this->prefix ?? null;
    }
}