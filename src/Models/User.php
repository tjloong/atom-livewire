<?php

namespace Jiannius\Atom\Models;

use Illuminate\Validation\Rule;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Password;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Jiannius\Atom\Traits\HasTrace;
use Jiannius\Atom\Traits\HasFilters;
use Jiannius\Atom\Notifications\ActivateAccountNotification;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;
    use SoftDeletes;
    use HasFactory;
    use HasApiTokens;
    use HasTrace;
    use HasFilters;

    protected $guarded = ['password'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'account_id' => 'integer',
        'activated_at' => 'datetime',
        'email_verified_at' => 'datetime',
    ];

    const ROOT_EMAIL = 'root@jiannius.com';

    /**
     * Get user home
     */
    public function home()
    {
        if ($this->isAccountType('signup')) return '/';
        else return route('app.home');
    }

    /**
     * Get account for user
     */
    public function account()
    {
        return $this->belongsTo(get_class(model('account')));
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

        return $this->belongsToMany(get_class(model('team')), 'teams_users');
    }

    /**
     * Scope for fussy search
     * 
     * @param Builder $query
     * @param string $search
     * @return Builder
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
     * 
     * @param Builder $query
     * @param string $name
     * @return Builder
     */
    public function scopeWhereIsRole($query, $name)
    {
        if (!enabled_module('roles')) abort_module('roles');

        return $query->whereHas('role', fn($q) => $q->where('slug', $name));
    }

    /**
     * Scope for team id
     * 
     * @param Builder $query
     * @param integer $id
     * @return Builder
     */
    public function scopeTeamId($query, $id)
    {
        if (!enabled_module('teams')) abort_module('teams');

        return $query->whereHas('teams', fn($q) => $q->whereIn('teams.id', (array)$id));
    }

    /**
     * Scope for status
     * 
     * @param Builder $query
     * @param mixed $statuses
     * @return Builder
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
     * 
     * @return string
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
     * 
     * @param mixed $names
     * @return boolean
     */
    public function isRole($names)
    {
        if (!enabled_module('roles')) abort_module('roles');
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
     * 
     * @return void
     */
    public function sendAccountActivation()
    {
        if ($this->status === 'inactive') {
            $this->notify(new ActivateAccountNotification());
        }
    }

    /**
     * Send password reset link
     * 
     * @return void
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
        return [
            'app' => Route::has('app.home') 
                && in_array($this->account->type, ['root', 'system']),

            'billing' => Route::has('billing') 
                && model('plan')->whereIsActive(true)->count() > 0
                && in_array($this->account->type, ['root', 'signup']),

            'ticketing' => Route::has('ticketing.listing') 
                && in_array($this->account->type, ['root', 'signup', 'system']),

            'onboarding' => Route::has('onboarding') 
                && in_array($this->account->type, ['root', 'signup']),
        ][$portal] ?? false;
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
