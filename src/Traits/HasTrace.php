<?php

namespace Jiannius\Atom\Traits;

use App\Models\User;
use Illuminate\Support\Facades\Schema;

trait HasTrace
{
    public $enabledHasTraceTrait = true;

    /**
     * Model boot method
     * 
     * @return void
     */
    protected static function bootHasTrace()
    {
        static::creating(function ($model) {
            $table = $model->getTable();

            if (auth()->hasUser()) {
                if (Schema::hasColumn($table, 'owned_by')) $model->owned_by = auth()->id();
                if (Schema::hasColumn($table, 'created_by')) $model->created_by = auth()->id();
            }
        });

        static::updating(function ($model) {
            $table = $model->getTable();

            if (auth()->hasUser() && Schema::hasColumn($table, 'owned_by') && !$model->owned_by) {
                $model->owned_by = $model->created_by;
            }
        });

        static::deleted(function ($model) {
            $table = $model->getTable();

            if (auth()->hasUser() && Schema::hasColumn($table, 'deleted_by') && $model->exists) {
                $model->deleted_by = auth()->id();
                $model->save();
            }
        });
    }

    /**
     * Initialize the trait
     * 
     * @return void
     */
    protected function initializeHasTrace()
    {
        $this->casts['owned_by'] = 'integer';
        $this->casts['created_by'] = 'integer';
        $this->casts['deleted_by'] = 'integer';
        $this->casts['blocked_by'] = 'integer';
        $this->casts['blocked_at'] = 'datetime';
    }

    /**
     * Get owned by user for model
     */
    public function owned_by_user()
    {
        return $this->belongsTo(User::class, 'owned_by');
    }

    /**
     * Get created_by_user for model
     */
    public function created_by_user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get deleted_by_user for model
     */
    public function deleted_by_user()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    /**
     * Get blocked_by_user for model
     */
    public function blocked_by_user()
    {
        return $this->belongsTo(User::class, 'blocked_by');
    }

    /**
     * Check model is blocked
     */
    public function blocked()
    {
        return $this->blocked_at && $this->blocked_at->lessThan(now());
    }

    /**
     * Block the model
     */
    public function block()
    {
        $this->blocked_at = now();
        $this->blocked_by = auth()->id();
        $this->saveQuietly();
    }

    /**
     * Unblock the model
     */
    public function unblock()
    {
        $this->blocked_at = null;
        $this->blocked_by = null;
        $this->saveQuietly();
    }
}