<?php

namespace Jiannius\Atom\Traits;

use Illuminate\Support\Facades\DB;

trait HasUniqueNumber
{
    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function bootHasUniqueNumber()
    {
        // listen to creating event
        static::saving(function ($model) {
            if (!$model->number) {
                $table = $model->getTable();
                $duplicated = true;

                while ($duplicated) {
                    $prefix = $model->getUniqueNumberPrefix();
                    $random = rand(100000, 999999);
                    $number = implode('-', array_filter([$prefix, $random]));
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
    protected function getUniqueNumberPrefix()
    {
        if (method_exists($this, 'uniqueNumberPrefix')) return $this->uniqueNumberPrefix();
        else return $this->uniqueNumberPrefix ?? date('ymd');
    }
}