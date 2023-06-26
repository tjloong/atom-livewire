<?php

namespace Jiannius\Atom\Traits\Models;

use Illuminate\Support\Facades\DB;

trait HasCode
{
    public $usesHasCode = true;

    // boot
    protected static function bootHasCode(): void
    {
        static::saving(function ($model) {
            $model->code = $model->code ?? $model->generateCode();
        });
    }

    // find by code
    public function scopeFindByCode($query, $code)
    {
        return $query->where('code', $code)->first();
    }

    // find by code or fail
    public function scopeFindByCodeOrFail($query, $code)
    {
        return $query->where('code', $code)->firstOrFail();
    }

    // generate code
    public function generateCode(): string
    {
        $code = null;
        $dup = true;

        while ($dup) {
            $code = str()->upper(str()->random($this->codeLength ?? 6));
            $dup = DB::table($this->getTable())
                ->when(has_column($this->getTable(), 'tenant_id'), fn($q) => $q->where('tenant_id', $this->tenant_id))
                ->where('code', $code)
                ->count() > 0;
        }

        return $code;
    }
}