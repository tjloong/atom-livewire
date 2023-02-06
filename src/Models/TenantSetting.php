<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Model;

class TenantSetting extends Model
{
    protected $guarded = [];

    protected $casts = [
        'tenant_id' => 'integer',
    ];

    /**
     * Get tenant for setting
     */
    public function tenant()
    {
        return $this->belongsTo(model('tenant'));
    }
}
