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

    // get owned by for model
    public function ownedBy()
    {
        return $this->belongsTo(model('user'), 'owned_by');
    }

    // get created by for model
    public function createdBy()
    {
        return $this->belongsTo(model('user'), 'created_by');
    }

    // get updated by for model
    public function updatedBy()
    {
        return $this->belongsTo(model('user'), 'updated_by');
    }

    // get deleted by for model
    public function deletedBy()
    {
        return $this->belongsTo(model('user'), 'deleted_by');
    }

    // get blocked by for model
    public function blockedBy()
    {
        return $this->belongsTo(model('user'), 'blocked_by');
    }

    // get closed by for model
    public function closedBy()
    {
        return $this->belongsTo(model('user'), 'closed_by');
    }

    // get refunded by for model
    public function refundedBy()
    {
        return $this->belongsTo(model('user'), 'refunded_by');
    }

    // get requested by for model
    public function requestedBy()
    {
        return $this->belongsTo(model('user'), 'requested_by');
    }

    // get approved by for model
    public function approvedBy()
    {
        return $this->belongsTo(model('user'), 'approved_by');
    }

    // get rejected by for model
    public function rejectedBy()
    {
        return $this->belongsTo(model('user'), 'rejected_by');
    }

    // get email sent by for model
    public function emailSentBy()
    {
        return $this->belongsTo(model('user'), 'email_sent_by');
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