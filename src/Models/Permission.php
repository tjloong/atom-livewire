<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Jiannius\Atom\Traits\Models\HasFilters;

class Permission extends Model
{
    use HasFactory;
    use HasFilters;
    
    protected $guarded = [];

    public $timestamps = false;

    public $permissions = [];

    // booted
    protected static function booted(): void
    {
        static::saved(function() {
            session()->forget('permissions');
        });
    }

    // get user for permission
    public function user(): BelongsTo
    {
        return $this->belongsTo(model('user'));
    }

    // get permission list
    public function getPermissionList(): array
    {
        return $this->permissions;
    }
}
