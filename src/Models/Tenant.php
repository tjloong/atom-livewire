<?php

namespace Jiannius\Atom\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Jiannius\Atom\Traits\Models\HasFilters;

class Tenant extends Model
{
    use HasFilters;
    
    protected $guarded = [];

    protected $casts = [
        'avatar_id' => 'integer',
    ];

    /**
     * Model booted
     */
    protected static function booted(): void
    {
        static::saved(function($tenant) {
            if (($sess = session('tenant.current')) && $sess->id === $tenant->id) {
                session()->forget('tenant.current');
            }
        });
    }

    /**
     * Get avatar for tenant
     */
    public function avatar(): BelongsTo
    {
        return $this->belongsTo(model('file'), 'avatar_id');
    }

    /**
     * Get users for tenant
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(model('user'), 'tenant_users');
    }

    /**
     * Get settings for tenant
     */
    public function settings(): HasMany
    {
        return $this->hasMany(model('tenant_setting'));
    }

    /**
     * Scope for fussy search
     */
    public function scopeSearch($query, $search): void
    {
        $query->where(fn($q) => $q
            ->where('name', 'like', "%$search%")
            ->whereHas('users', fn($q) => $q->search($search))
        );
    }

    /**
     * Get address attribute
     */
    public function getAddressAttribute(): string
    {
        return format_address($this);
    }

    /**
     * Get owner attribute
     */
    public function getOwnerAttribute(): User
    {
        return $this->users()->wherePivot('is_owner', true)->first();
    }

    /**
     * Current tenant
     */
    public function current(): mixed
    {
        return model('tenant')
            ->whereHas('users', fn($q) => $q->where('users.id', user('id')))
            ->when(user('pref.tenant'), fn($q, $id) => $q->where('id', $id))
            ->oldest()
            ->first();
    }

    /**
     * Retrieve tenant
     */
    public function retrieve($attr = null, $default = null, $tenant = null): mixed
    {
        $tenant = $tenant ?? session('tenant.current') ?? model('tenant')->current();
        $settings = model('tenant_setting')->retrieve($tenant);

        if (!session('tenant.current')) session(['tenant.current' => $tenant]);

        if ($attr === 'settings') return $settings;
        else if (is_string($attr)) {
            if (str($attr)->is('settings.*')) {
                $attr = str($attr)->replaceFirst('settings.', '')->replace('-', '_')->toString();
                $split = explode('.', $attr);
                $key = $split[0];
                $subkeys = collect($split)->reject($key)->join('.');
                $settings = data_get($settings, $key);
                $value = json_decode(json_encode($settings ?? $default), true);
    
                if ($subkeys) return data_get($value, $subkeys);
                else return $value;
            }
            else {
                return data_get($tenant, $attr, $default);
            }
        }
        else if (is_array($attr)) {
            foreach ($attr as $key => $val) {
                if (str($key)->is('settings.*')) {
                    $key = str($key)->replaceFirst('settings.', '')->replace('-', '_')->toString();
                    $settings = $tenant->settings()->where('key', $key)->get();

                    if ($settings->count()) {
                        return $settings->each(fn($setting) => $setting->fill(['value' => $val])->save());
                    }
                    else {
                        return $tenant->settings()->create([
                            'key' => $key,
                            'value' => $val,
                        ]);
                    }
                }
                else return $tenant->fill([$key => $val])->save();
            }
        }
        else {
            return $tenant;
        }    
    }
}
