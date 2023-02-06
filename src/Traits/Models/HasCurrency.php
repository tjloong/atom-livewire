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
        return (
            $this->enabledHasTenantTrait
                ? tenant_settings('default_currency')
                : site_settings('default_currency')
        ) ?? config('atom.default_currency') ?? env('DEFAULT_CURRENCY');
    }

    /**
     * Get currency options attribute
     */
    public function getCurrencyOptionsAttribute()
    {
        $options = (
            $this->enabledHasTenantTrait
                ? tenant_settings('currencies')
                : site_settings('currencies')
        ) ?? config('atom.currencies') ?? env('CURRENCIES');

        return $options ?? [
            'currency' => $this->master_currency,
            'rate' => 1,
        ];
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