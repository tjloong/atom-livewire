<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Model;

class TenantSetting extends Model
{
    public $timestamps = false;

    protected $guarded = [];

    protected $casts = [
        'value' => 'object',
        'tenant_id' => 'integer',
    ];

    /**
     * Model booted
     */
    protected static function booted()
    {
        static::saved(function($setting) {
            $setting->tenant->touch();
        });
    }

    /**
     * Get tenant for setting
     */
    public function tenant()
    {
        return $this->belongsTo(model('tenant'));
    }
}
