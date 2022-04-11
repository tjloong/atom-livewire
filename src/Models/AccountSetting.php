<?php

namespace Jiannius\Atom\Models;

use Jiannius\Atom\Traits\HasFilters;
use Illuminate\Database\Eloquent\Model;

class AccountSetting extends Model
{
    use HasFilters;
    
    protected $guarded = [];

    protected $casts = [
        'account_id' => 'integer',
    ];

    /**
     * Get account for account setting
     */
    public function account()
    {
        return $this->belongsTo(get_class(model('account')));
    }
}
