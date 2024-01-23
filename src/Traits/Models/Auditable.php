<?php

namespace Jiannius\Atom\Traits\Models;

use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Auditable
{
    public $auditCacheKey;
    public $excludedAuditAttributes = [];

    // boot
    protected static function bootAuditable() : void
    {
        static::creating(fn($model) => $model->storeAuditOriginalValues());
        static::updating(fn($model) => $model->storeAuditOriginalValues());
        static::deleting(fn($model) => $model->storeAuditOriginalValues());

        static::created(function($model) {
            $model->audit('created');
        });

        static::updated(function($model) {
            $model->audit('updated');
        });

        static::deleted(function($model) {
            if ($model->exists) $model->audit('trashed');
            else $model->audit('deleted');
        });
    }

    // initialize
    protected function initializeAuditable() : void
    {
        $this->auditCacheKey = 'audit_'.str()->random();
    }

    // get audits for model
    public function audits() : MorphMany
    {
        return $this->morphMany(model('audit'), 'auditable');
    }

    // get auditable old values
    public function getAuditableOldValues() : array
    {
        $original = cache($this->auditCacheKey);
        $new = $this->getAuditableNewValues();
        $values = $this->filterAuditableAttributes(
            collect($new)->map(fn($val, $key) => data_get($original, $key))->toArray()
        );

        return $this->transformAuditValues($values);
    }

    // get auditable new values
    public function getAuditableNewValues() : array
    {
        $changes = $this->getChanges();
        $original = cache($this->auditCacheKey);
        $values = $this->filterAuditableAttributes(
            collect($changes)->filter(fn($val, $key) => data_get($original, $key) !== $val)->toArray()
        );

        return $this->transformAuditValues($values);
    }

    // filter auditable attributes
    public function filterAuditableAttributes($attributes) : array
    {
        $excludes = array_merge($this->excludedAuditAttributes, [
            'updated_at',
            'footprint',
        ]);

        return collect($attributes)
            ->filter(fn($val, $key) => !collect($excludes)->contains($key))
            ->toArray();
    }

    // transform audit values
    public function transformAuditValues($values, $attrs = []) : array
    {
        $values = collect($values);

        if ($attrs) $values = $values->filter(fn($value, $key) => in_array($key, $attrs));

        return $values->map(function($value, $key) {
            if (str($key)->is('*_by') && is_numeric($value)) {
                return optional(model('user')->find($value))->name ?? $value;
            }
            elseif (str($key)->is('*_at') && !empty($value)) {
                return format($value, 'datetime')->value();
            }
            else return $value;
        })
        ->toArray();
    }

    // get audit data
    public function getAuditData($event) : array
    {
        return [];
    }

    // audit - this will be called to create audit
    public function audit($event, $data = []) : void
    {
        if ($event === 'created') {
            $this->createAuditEntry([
                'event' => 'created',
                'new_values' => $this->getAuditableNewValues()
            ]);
        }
        elseif ($event === 'updated') {
            $oldValues = $this->getAuditableOldValues();
            $newValues = $this->getAuditableNewValues();

            if ($oldValues || $newValues) {
                $this->createAuditEntry([
                    'event' => 'updated',
                    'old_values' => $oldValues,
                    'new_values' => $newValues,
                ]);
            }
        }
        else {
            $this->createAuditEntry(compact('event'));
        }

        cache()->forget($this->auditCacheKey);
    }

    // create audit entry
    public function createAuditEntry($data) : void
    {
        $this->audits()->create(array_merge(
            $this->getAuditData(data_get($data, 'event')),
            $data,
        ));
    }

    // store audit original values
    public function storeAuditOriginalValues() : void
    {
        cache()->put($this->auditCacheKey, $this->getOriginal(), now()->addMinutes(5));
    }
}