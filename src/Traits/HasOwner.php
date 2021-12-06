<?php

namespace Jiannius\Atom\Traits;

use App\Models\User;
use Illuminate\Support\Facades\Schema;

trait HasOwner
{
    public $enabledHasOwnerTrait = true;

    /**
     * Model boot method
     * 
     * @return void
     */
    protected static function bootHasOwner()
    {
        static::creating(function ($model) {
            if (auth()->hasUser()) {
                if (Schema::hasColumn($model->getTable(), 'owned_by')) $model->owned_by = auth()->id();
                if (Schema::hasColumn($model->getTable(), 'created_by')) $model->created_by = auth()->id();
            }
        });

        static::updating(function ($model) {
            if (
                auth()->hasUser()
                && Schema::hasColumn($model->getTable(), 'owned_by')
                && !$model->owned_by
            ) {
                $model->owned_by = $model->created_by;
            }
        });

        static::addGlobalScope('owner', function ($query) {
            if (auth()->hasUser()) $query->accessibleByUser();
        });
    }

    /**
     * Initialize the trait
     * 
     * @return void
     */
    protected function initializeHasOwner()
    {
        $this->fillable[] = 'owned_by';
        $this->fillable[] = 'created_by';
        
        $this->casts['owned_by'] = 'integer';
        $this->casts['created_by'] = 'integer';
    }

    /**
     * Get owner for model
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owned_by');
    }

    /**
     * Get creator for model
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope for accessibility by user
     * 
     * @param Builder $query
     * @param User $user
     * @return Builder
     */
    public function scopeAccessibleByUser($query, $user = null)
    {
        if (!Schema::hasColumn($this->getTable(), 'owned_by')) return $query;

        $user = $user ?? auth()->user();

        if ($user->enabledHasRoleTrait) {
            if ($user->isRole('root')) return $query;

            $access = $user->role->access ?? null;
            
            if (!$access) abort(401);
            else if ($access === 'global') return $query;
            else if ($access === 'restrict') return $query->where('owned_by', $user->id);
            else if ($access === 'team' && $user->enabledHasTeamTrait) {
                return $query->whereHas('owner', 
                    fn($q) => $q->teamId($user->teams->pluck('id')->toArray())
                );
            }
        }
        else {
            return $query->where(
                fn($q) => $q
                    ->where(fn($q) => $q->whereNull('owned_by')->where('created_by', $user->id))
                    ->orWhere(fn($q) => $q->whereNotNull('owned_by')->where('owned_by', $user->id))
            );
        }
    }
}