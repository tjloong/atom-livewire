<?php

namespace Jiannius\Atom\Traits\Models;

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
            'email_sent_at',
        ] as $col) {
            $this->casts[$col] = 'datetime';
        }
    }

    // trace
    public function trace($key, $default = null) : mixed
    {
        $table = $this->getTable();
        $splits = collect(explode('.', $key));
        $col = $splits->first();

        if (has_column($table, $col)) {
            $trace = $this->belongsTo(model('user'), $col)->getResults();
            $splits->shift();
            $attr = $splits->join('.');

            return $attr
                ? data_get($trace, $splits->join('.'), $default)
                : $trace;
        }

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
}