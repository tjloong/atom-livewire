<?php

namespace Jiannius\Atom\Traits\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait HasTrace
{
    public $enabledHasTraceTrait = true;

    // boot
    protected static function bootHasTrace() : void
    {
        static::creating(function ($model) {
            $table = $model->getTable();

            if ($id = auth()->user()->id ?? null) {
                foreach ([
                    'owned_by',
                    'created_by',
                    'updated_by',
                    'requested_by',
                ] as $col) {
                    if (has_column($table, $col) && empty($model->$col)) {
                        $model->fill([$col => $id]);
                    }
                }
            }
        });

        static::updating(function ($model) {
            $table = $model->getTable();

            if ($id = auth()->user()->id ?? null) {
                if (has_column($table, 'updated_by')) $model->updated_by = $id;
                if (has_column($table, 'deleted_by') && !$model->deleted_at) $model->deleted_by = null;
            }
        });

        static::deleted(function ($model) {
            $table = $model->getTable();

            if (has_column($table, 'deleted_by') && $model->exists) {
                if ($id = auth()->user()->id ?? null) {
                    $model->fill(['deleted_by' => $id])->saveQuietly();
                }
            }
        });
    }

    // initialize
    protected function initializeHasTrace() : void
    {
        foreach ([
            'blocked_at',
            'closed_at',
            'refunded_at',
            'requested_at',
            'approved_at',
            'rejected_at',
            'archived_at',
            'completed_at',
            'email_sent_at',
        ] as $col) {
            $this->casts[$col] = 'datetime';
        }

        $this->with = array_merge($this->with, [
            'trace_created_by',
            'trace_updated_by',
            'trace_owned_by',
            'trace_deleted_by',
            'trace_blocked_by',
            'trace_closed_by',
            'trace_refunded_by',
            'trace_requested_by',
            'trace_approved_by',
            'trace_rejected_by',
            'trace_archived_by',
            'trace_completed_by',
            'trace_email_sent_by',
        ]);
    }

    // get created_by for model
    public function trace_created_by() : BelongsTo
    {
        return $this->belongsTo(model('user'), 'created_by');
    }

    // get updated_by for model
    public function trace_updated_by() : BelongsTo
    {
        return $this->belongsTo(model('user'), 'updated_by');
    }

    // get owned_by for model
    public function trace_owned_by() : BelongsTo
    {
        return $this->belongsTo(model('user'), 'owned_by');
    }

    // get deleted_by for model
    public function trace_deleted_by() : BelongsTo
    {
        return $this->belongsTo(model('user'), 'deleted_by');
    }

    // get blocked_by for model
    public function trace_blocked_by() : BelongsTo
    {
        return $this->belongsTo(model('user'), 'blocked_by');
    }

    // get closed_by for model
    public function trace_closed_by() : BelongsTo
    {
        return $this->belongsTo(model('user'), 'closed_by');
    }

    // get refunded_by for model
    public function trace_refunded_by() : BelongsTo
    {
        return $this->belongsTo(model('user'), 'refunded_by');
    }

    // get requested_ for model
    public function trace_requested_by() : BelongsTo
    {
        return $this->belongsTo(model('user'), 'requested_');
    }

    // get approved_by for model
    public function trace_approved_by() : BelongsTo
    {
        return $this->belongsTo(model('user'), 'approved_by');
    }

    // get rejected_by for model
    public function trace_rejected_by() : BelongsTo
    {
        return $this->belongsTo(model('user'), 'rejected_by');
    }

    // get archived_by for model
    public function trace_archived_by() : BelongsTo
    {
        return $this->belongsTo(model('user'), 'archived_by');
    }

    // get completed_by for model
    public function trace_completed_by() : BelongsTo
    {
        return $this->belongsTo(model('user'), 'completed_by');
    }

    // get email_sent for model
    public function trace_email_sent_by() : BelongsTo
    {
        return $this->belongsTo(model('user'), 'email_sent');
    }

    // scope for trace
    public function scopeWhereTrace($query, $key, $search) : void
    {
        if ($search) {
            $table = $this->getTable();
            $splits = collect(explode('.', $key));
            $col = $splits->shift();
            $attrs = $splits;
            $user = model('user')->whereRaw("`users`.`id` = `$table`.`$col`");

            if ($attrs->count()) {
                foreach ($attrs as $attr) {
                    $user->where('users.'.$attr, $search);
                }
            }
            else $user->search($search);

            $query->whereExists($user);
        }
    }

    // scope for or where trace
    public function scopeOrWhereTrace($query, $key, $search) : void
    {
        $query->orWhere(fn($q) => $q->whereTrace($key, $search));
    }

    // trace
    public function trace($key, $default = null) : mixed
    {
        $splits = collect(explode('.', $key));
        $col = $splits->shift();
        $attr = $splits->join('.');

        if ($key === $col) return $this->{'trace_'.$col};
        else return data_get($this->{'trace_'.$col}, $attr, $default);

        return null;
    }

    // check model is blocked
    public function isBlocked() : bool
    {
        return $this->blocked_at && $this->blocked_at->lessThan(now());
    }

    // check model is closed
    public function isClosed() : bool
    {
        return $this->closed_at && $this->closed_at->lessThan(now());
    }

    // check model is refunded
    public function isRefunded() : bool
    {
        return $this->refunded_at && $this->refunded_at->lessThan(now());
    }

    // check model is approved
    public function isApproved() : bool
    {
        return $this->approved_at && $this->approved_at->lessThan(now());
    }

    // check model is rejected
    public function isRejected() : bool
    {
        return $this->rejected_at && $this->rejected_at->lessThan(now());
    }

    // check model is email sent
    public function isEmailSent() : bool
    {
        return !empty($this->email_sent_at);
    }

    // check model is archived
    public function isArchived() : bool
    {
        return !empty($this->archived_at);
    }

    // check model is completed
    public function isCompleted() : bool
    {
        return !empty($this->completed_at);
    }

    // mark as blocked
    public function markBlocked($bool = true) : void
    {
        $this->fill([
            'blocked_at' => $bool === false ? null : now(),
            'blocked_by' => $bool === false ? null : user('id'),
        ])->save();
    }

    // mark as closed
    public function markClosed($bool = true) : void
    {
        $this->fill([
            'closed_at' => $bool === false ? null : now(),
            'closed_by' => $bool === false ? null : user('id'),
        ])->save();
    }

    // mark as refunded
    public function markRefunded($bool = true) : void
    {
        $this->fill([
            'refunded_at' => $bool === false ? null : now(),
            'refunded_by' => $bool === false ? null : user('id'),
        ])->save();
    }

    // mark as approved
    public function markApproved($bool = true) : void
    {
        $this->fill([
            'approved_at' => $bool === false ? null : now(),
            'approved_by' => $bool === false ? null : user('id'),
        ])->save();
    }
    
    // mark as rejected
    public function markRejected($bool = true) : void
    {
        $this->fill([
            'rejected_at' => $bool === false ? null : now(),
            'rejected_by' => $bool === false ? null : user('id'),
        ])->save();
    }
    
    // mark email sent
    public function markEmailSent($bool = true) : void
    {
        $this->fill([
            'email_sent_at' => $bool === false ? null : now(),
            'email_sent_by' => $bool === false ? null : user('id'),
        ])->save();
    }

    // mark as archived
    public function markArchived($bool = true) : void
    {
        $this->fill([
            'archived_at' => $bool === false ? null : now(),
            'archived_by' => $bool === false ? null : user('id'),
        ])->save();
    }

    // mark as completed
    public function markCompleted($bool = true) : void
    {
        $this->fill([
            'completed_at' => $bool === false ? null : now(),
            'completed_by' => $bool === false ? null : user('id'),
        ])->save();
    }
}