<?php

namespace Jiannius\Atom\Models;

use Jiannius\Atom\Traits\HasFilters;
use Illuminate\Database\Eloquent\Model;

class AccountSetting extends Model
{
    use HasFilters;
    
    protected $table = 'accounts_settings';

    protected $guarded = [];

    protected $casts = [
        'account_id' => 'integer',
    ];

    /**
     * Get account for account settings
     */
    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
