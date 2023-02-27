<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Model;
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
    protected static function booted()
    {
        static::saved(function($tenant) {
            if (($sess = session('tenant')) && $sess->id === $tenant->id) {
                session()->forget('tenant');
            }
        });
    }

    /**
     * Get avatar for tenant
     */
    public function avatar()
    {
        return $this->belongsTo(model('file'), 'avatar_id');
    }

    /**
     * Get users for tenant
     */
    public function users()
    {
        return $this->belongsToMany(model('user'), 'tenant_users');
    }

    /**
     * Get settings for tenant
     */
    public function settings()
    {
        return $this->hasMany(model('tenant_setting'));
    }

    /**
     * Scope for fussy search
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(fn($q) => $q
            ->where('name', 'like', "%$search%")
            ->whereHas('users', fn($q) => $q->search($search))
        );
    }

    /**
     * Scope for current tenant
     */
    public function scopeCurrent($query)
    {
        return $query
            ->whereHas('users', fn($q) => $q->where('users.id', user('id')))
            ->when(user('pref.tenant'), fn($q, $id) => $q->where('id', $id))
            ->oldest();
    }

    /**
     * Get address attribute
     */
    public function getAddressAttribute()
    {
        return format_address($this);
    }

    /**
     * Get owner attribute
     */
    public function getOwnerAttribute()
    {
        return $this->users()->wherePivot('is_owner', true)->first();
    }

    /**
     * Retrieve tenant
     */
    public function retrieve($attr = null, $default = null, $tenant = null)
    {
        if (!$tenant) $tenant = session('tenant') ?? model('tenant')->current()->first();
    
        if ($attr === 'settings') {
            return $tenant->settings->mapWithKeys(fn($setting) => [
                $setting->key => $setting->value,
            ]);
        }
        else if (is_string($attr)) {
            if (str($attr)->is('settings.*')) {
                $attr = str($attr)->replaceFirst('settings.', '')->toString();
                $split = explode('.', $attr);
                $key = $split[0];
                $subkeys = collect($split)->reject($key)->join('.');
                $settings = $tenant->settings()->where('key', $key)->first();
                $value = json_decode(json_encode(optional($settings)->value ?? $default), true);
    
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
                    $key = str($key)->replaceFirst('settings.', '')->toString();
                    $settings = $tenant->settings()->where('key', $key)->get();

                    if ($settings->count()) {
                        $settings->each(fn($setting) => $setting->fill(['value' => $val])->save());
                    }
                    else {
                        $tenant->settings()->create([
                            'key' => $key,
                            'value' => $val,
                        ]);
                    }
                }
                else $tenant->fill([$key => $val])->save();
            }
        }
        else {
            return $tenant;
        }    
    }
}
