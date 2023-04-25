<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TenantSetting extends Model
{
    public $timestamps = false;

    protected $guarded = [];

    protected $casts = [
        'value' => 'object',
        'tenant_id' => 'integer',
    ];

    protected $touches = ['tenant'];

    /**
     * Model booted
     */
    protected static function booted(): void
    {
        static::saving(function() {
            session()->forget('tenant.settings');
        });
    }

    /**
     * Get tenant for setting
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(model('tenant'));
    }

    /**
     * Retrieve settings for tenant
     */
    public function retrieve($tenant)
    {
        $settings = session('tenant.settings') ?? ($tenant 
            ? $tenant->settings->mapWithKeys(fn($setting) => [$setting->key => $setting->value])->toArray()
            : []
        );

        if (!session('tenant.settings')) session(['tenant.settings' => $settings]);

        return $settings;
    }
}
