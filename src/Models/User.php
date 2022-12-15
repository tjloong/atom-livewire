<?php

namespace Jiannius\Atom\Models;

use Illuminate\Validation\Rule;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Password;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Jiannius\Atom\Traits\Models\HasTrace;
use Jiannius\Atom\Traits\Models\HasFilters;
use Jiannius\Atom\Traits\Models\HasVisibility;
use Jiannius\Atom\Notifications\ActivateAccountNotification;
use Jiannius\Atom\Traits\Models\BelongsToAccount;

class User extends Authenticatable implements MustVerifyEmail
{
    use BelongsToAccount;
    use HasApiTokens;
    use HasFactory;
    use HasFilters;
    use HasTrace;
    use HasVisibility;
    use Notifiable;
    use SoftDeletes;

    protected $guarded = ['password'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'activated_at' => 'datetime',
        'login_at' => 'datetime',
        'last_active_at' => 'datetime',
        'email_verified_at' => 'datetime',
    ];

    const ROOT_EMAIL = 'root@jiannius.com';

    /**
     * Model boot
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function($user) {
            $user->sendAccountActivation();
        });

        static::updated(function($user) {
            if ($user->isDirty('email') && config('atom.accounts.verify')) {
                $user->fill(['email_verified_at' => null])->saveQuietly();
                $user->sendEmailVerificationNotification();
            }
        });
    }

    /**
     * Get user home
     */
    public function home()
    {
        if ($this->isAccountType('signup')) return '/';
        else return route('app.home');
    }

    /**
     * Get role for user
     */
    public function role()
    {
        if (!enabled_module('roles')) return;

        return $this->belongsTo(get_class(model('role')));
    }

    /**
     * Get permissions for user
     */
    public function permissions()
    {
        if (!enabled_module('permissions')) return;

        return $this->hasMany(get_class(model('user_permission')));
    }
    
    /**
     * Get teams for user
     */
    public function teams()
    {
        if (!enabled_module('teams')) return;

        return $this->belongsToMany(get_class(model('team')), 'team_users');
    }

    /**
     * Scope for fussy search
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(fn($q) => $q
            ->where('name', 'like', "%$search%")
            ->orWhere('email', 'like', "%$search%")
            ->orWhereHas('account', fn($q) => $q->search($search))
        );
    }

    /**
     * Scope for is role
     */
    public function scopeIsRole($query, $name)
    {
        return $query->when(
            enabled_module('roles'), 
            fn($q) => $q->whereHas('role', fn($q) => $q->where('slug', $name)) 
        );
    }

    /**
     * Scope for in team
     */
    public function scopeInTeam($query, $id)
    {
        return $query->when(
            enabled_module('teams'),
            fn($q) => $q->whereHas('teams', fn($q) => $q->whereIn('teams.id', (array)$id))
        );
    }

    /**
     * Scope for status
     */
    public function scopeStatus($query, $statuses)
    {
        $statuses = (array)$statuses;

        if (in_array('trashed', $statuses)) $query->onlyTrashed();

        return $query->where(function ($q) use ($statuses) { 
            foreach ($statuses as $status) {
                if ($status === 'blocked') $q->orWhere(fn($q) => $q->whereNotNull('blocked_at')->whereRaw('blocked_at <= now()'));
                else if ($status === 'inactive') $q->orWhere(fn($q) => $q->whereNull('activated_at')->orWhereRaw('activated_at > now()'));
                else if ($status === 'active') {
                    $q->orWhere(fn($q) => 
                        $q
                        ->where(fn($q) => $q->whereNull('deleted_at')->orWhereRaw('deleted_at > now()'))
                        ->where(fn($q) => $q->whereNull('blocked_at')->orWhereRaw('blocked_at > now()'))
                        ->whereRaw('activated_at <= now()')
                    );
                }
            }
        });
    }

    /**
     * Get status attribute
     */
    public function getStatusAttribute()
    {
        if ($this->trashed()) return 'trashed';
        if ($this->blocked()) return 'blocked';
        
        if ($this->activated_at) return 'active';
        else return 'inactive';
    }

    /**
     * Check user is account type
     */
    public function isAccountType($type)
    {
        if (!$this->account) return false;

        return in_array($this->account->type, (array)$type);
    }

    /**
     * Check user is role
     */
    public function isRole($names)
    {
        if (!enabled_module('roles')) return true;
        if (!$this->role) return false;

        return collect((array)$names)->filter(function($name) {
            $substr = str()->slug(str_replace('*', '', $name));

            if (str()->startsWith($name, '*')) return str()->endsWith($this->role->slug, $substr);
            else if (str()->endsWith($name, '*')) return str()->startsWith($this->role->slug, $substr);
            else return $this->role->slug === $name;
        })->count() > 0;
    }

    /**
     * Invite user to activate account
     */
    public function sendAccountActivation()
    {
        if ($this->status === 'inactive') {
            $this->notify(new ActivateAccountNotification());
        }
    }

    /**
     * Send password reset link
     */
    public function sendPasswordResetLink()
    {
        $status = Password::sendResetLink(['email' => $this->email]);

        if ($status === Password::RESET_LINK_SENT) return $status;
        else return false;
    }

    /**
     * Check user can access portal
     */
    public function canAccessPortal($portal)
    {
        if ($portal === 'app') {
            return current_route([
                'app.settings', 
                'app.ticketing.*', 
                'app.billing.*',
                'app.onboarding.*',
            ]) || in_array($this->account->type, ['root', 'system']);
        }

        return true;
    }

    /**
     * Get user validation rules and messages
     */
    public function getValidation()
    {
        $validation = (object)[
            'rules' => [
                'user.name' => 'required',
                'user.email' => [
                    'required',
                    'email',
                    Rule::unique('users', 'email')->ignore($this),
                ],
                'user.visibility' => 'nullable',
                'user.activated_at' => 'nullable',
                'user.account_id' => 'nullable',    
            ],
            'messages' => [
                'user.name.required' => __('Name is required.'),
                'user.email.required' => __('Login email is required.'),
                'user.email.email' => __('Invalid email address.'),
                'user.email.unique' => __('Login email is already taken.'),    
            ],
        ];

        if (enabled_module('roles')) $validation->rules['role_id'] = 'nullable';

        return $validation;
    }
}
