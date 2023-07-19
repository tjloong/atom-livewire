<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Jiannius\Atom\Traits\Models\HasFilters;

class Tenant extends Model
{
    use HasFilters;
    
    protected $guarded = [];

    protected $casts = [
        'avatar_id' => 'integer',
    ];

    // booted
    protected static function booted(): void
    {
        static::saved(function($tenant) {
            $tenant->clearSessions();
        });
    }

    // get avatar for tenant
    public function avatar(): BelongsTo
    {
        return $this->belongsTo(model('file'), 'avatar_id');
    }

    // get users for tenant
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(model('user'), 'user_tenants')->withPivot([
            'visibility',
            'is_owner',
            'is_preferred',
        ]);
    }

    // get settings for tenant
    public function settings(): HasMany
    {
        return $this->hasMany(model('tenant_setting'));
    }

    // get invitations for tenant
    public function invitations(): mixed
    {
        if (!has_table('invitations')) return null;

        return $this->hasMany(model('tenant_invitation'));
    }

    // get permissions for tenant
    public function permissions(): mixed
    {
        if (!has_table('permissions')) return null;

        return $this->hasMany(model('permission'));
    }

    // attribute for address
    protected function address(): Attribute
    {
        return Attribute::make(
            get: fn() => format_address($this),
        );
    }

    // attribute for owner
    protected function owners(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->users()->wherePivot('is_owner', true)->get(),
        );
    }

    // scope for fussy search
    public function scopeSearch($query, $search): void
    {
        $query->where(fn($q) => $q
            ->where('name', 'like', "%$search%")
            ->whereHas('users', fn($q) => $q->search($search))
        );
    }

    // set user as owner for tenant
    public function setOwner($user, $isOwner = true): void
    {
        $id = is_numeric($user) ? $user : $user->id;
        $exists = $this->users()->where('users.id', $id)->count();

        if ($exists) $this->users()->updateExistingPivot($id, ['is_owner' => $isOwner]);
        else $this->users()->attach([$id => ['is_owner' => $isOwner]]);

        $this->clearSessions();
    }

    // set tenant as preferred for user
    public function setPreferred($user): void
    {
        $id = is_numeric($user) ? $user : $user->id;
        $exists = $this->users()->where('users.id', $id)->count();

        if (!$exists) $this->users()->attach($id);

        DB::table('user_tenants')->where('user_id', $id)->update(['is_preferred' => false]);
        DB::table('user_tenants')->where('user_id', $id)->where('tenant_id', $this->id)->update(['is_preferred' => true]);
        
        $this->clearSessions();
    }

    // current tenant
    public function current(): mixed
    {
        $query = DB::table('user_tenants')->where('user_id', user('id'));
        
        $pivot = (clone $query)->where('is_preferred', true)->latest('id')->first()
            ?? (clone $query)->where('is_owner', true)->latest('id')->first()
            ?? (clone $query)->latest('id')->first();

        return $pivot ? model('tenant')->find($pivot->tenant_id) : null;
    }

    // retrieve tenant
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

    // clear tenant sessions
    public function clearSessions(): void
    {
        session()->forget('tenant.current');
        session()->forget('tenant.settings');
        session()->forget('tenants');
    }
}
