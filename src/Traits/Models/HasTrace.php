<?php

namespace Jiannius\Atom\Traits\Models;

trait HasTrace
{
    public $enabledHasTraceTrait = true;

    // boot
    protected static function bootHasTrace()
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
    protected function initializeHasTrace()
    {
        foreach ([
            'blocked_at',
            'closed_at',
            'refunded_at',
            'requested_at',
            'approved_at',
            'rejected_at',
            'email_sent_at',
        ] as $col) {
            $this->casts[$col] = 'datetime';
        }
    }

    // trace
    public function trace($key) : mixed
    {
        $table = $this->getTable();
        $splits = collect(explode('.', $key));
        $col = $splits->first();

        if (has_column($table, $col)) {
            $trace = $this->belongsTo(model('user'), $col)->getResults();
            $splits->shift();
            $attr = $splits->join('.');

            return $attr
                ? data_get($trace, $splits->join('.'))
                : $trace;
        }

        return null;
    }

    // check model is blocked
    public function blocked()
    {
        return $this->blocked_at && $this->blocked_at->lessThan(now());
    }

    // check model is closed
    public function closed()
    {
        return $this->closed_at && $this->closed_at->lessThan(now());
    }

    // check model is refunded
    public function refunded()
    {
        return $this->refunded_at && $this->refunded_at->lessThan(now());
    }

    // check model is approved
    public function approved()
    {
        return $this->approved_at && $this->approved_at->lessThan(now());
    }

    // check model is rejected
    public function rejected()
    {
        return $this->rejected_at && $this->rejected_at->lessThan(now());
    }

    // check model is email sent
    public function emailSent()
    {
        return !empty($this->email_sent_at);
    }

    // block the model
    public function block()
    {
        $this->fill([
            'blocked_at' => now(),
            'blocked_by' => user('id'),
        ])->save();
    }

    // unblock the model
    public function unblock()
    {
        $this->fill([
            'blocked_at' => null,
            'blocked_by' => null,
        ])->save();
    }

    // close the model
    public function close()
    {
        $this->fill([
            'closed_at' => now(),
            'closed_by' => user('id'),
        ])->save();
    }

    // unclose the model
    public function unclose()
    {
        $this->fill([
            'closed_at' => null,
            'closed_by' => null,
        ])->save();
    }

    // refund the model
    public function refund()
    {
        $this->fill([
            'refunded_at' => now(),
            'refunded_by' => user('id'),
        ])->save();
    }

    // unrefund the model
    public function unrefund()
    {
        $this->fill([
            'refunded_at' => null,
            'refunded_by' => null,
        ])->save();
    }

    // approve the model
    public function approve()
    {
        $this->fill([
            'approved_at' => now(),
            'approved_by' => user('id'),
        ])->save();
    }
    
    // unapprove the model
    public function unapprove()
    {
        $this->fill([
            'approved_at' => null,
            'approved_by' => null,
        ])->save();
    }

    // reject the model
    public function reject()
    {
        $this->fill([
            'rejected_at' => now(),
            'rejected_by' => user('id'),
        ])->save();
    }
    
    // unreject the model
    public function unreject()
    {
        $this->fill([
            'rejected_at' => null,
            'rejected_by' => null,
        ])->save();
    }

    // mark email sent
    public function markEmailSent()
    {
        $this->fill([
            'email_sent_at' => now(),
            'email_sent_by' => user('id'),
        ]);
    }

    // mark email unsent
    public function markEmailUnsent()
    {
        $this->fill([
            'email_sent_at' => null,
            'email_sent_by' => null,
        ]);
    }
}