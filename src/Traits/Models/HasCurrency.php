<?php

namespace Jiannius\Atom\Traits\Models;

use Illuminate\Support\Facades\Schema;

trait HasCurrency
{
    public $enabledHasCurrencyTrait;

    /**
     * Get has currency rate attribute
     */
    public function getHasCurrencyRateColumnAttribute()
    {
        return Schema::hasColumn($this->getTable(), 'currency_rate');
    }

    /**
     * Get master currency attribute
     */
    public function getMasterCurrencyAttribute()
    {
        return tenant('settings.default_currency')
            ?? settings('default_currency')
            ?? config('atom.default_currency')
            ?? env('DEFAULT_CURRENCY');
    }

    /**
     * Get is foreign currency attribute
     */
    public function getIsForeignCurrencyAttribute()
    {
        return $this->has_currency_rate_column && $this->currency !== $this->master_currency;
    }

    /**
     * Calculate currency conversion
     */
    public function calculateCurrencyConversion($cols)
    {
        if (!$this->is_foreign_currency) return $this->cols;

        if (is_string($cols)) return $this->$cols * ($this->currency_rate ?? 1);

        return collect($cols)->mapWithKeys(fn($col) => 
            [$col => $this->col * ($this->currency_rate ?? 1)]
        );
    }
}