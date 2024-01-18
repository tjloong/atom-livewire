<?php

namespace Jiannius\Atom\Traits\Models;

use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Auditable
{
    public $excludedAuditAttributes = [];

    // boot
    protected static function bootAuditable() : void
    {
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

    // get audits for model
    public function audits() : MorphMany
    {
        return $this->morphMany(model('audit'), 'auditable');
    }

    // get auditable new values
    public function getAuditableNewValues() : array
    {
        $values = $this->filterAuditableAttributes($this->getDirty());

        return $this->transformAuditValues($values);
    }

    // get auditable old values
    public function getAuditableOldValues() : array
    {
        $newValues = $this->getAuditableNewValues();
        $oldValues = collect($newValues)->map(fn ($val, $key) => $this->getOriginal($key))->toArray();

        return $this->transformAuditValues($oldValues);
    }

    // filter auditable attributes
    public function filterAuditableAttributes($attributes) : array
    {
        $excludes = array_merge($this->excludedAuditAttributes, [
            'updated_at',
        ]);

        return collect($attributes)
            ->filter(fn($val, $key) => !collect($excludes)->contains($key))
            ->toArray();
    }

    // transform audit values
    public function transformAuditValues($values) : array
    {
        return collect($values)->map(function($value, $key) {
            if (str($key)->is('*_by') && is_numeric($value)) {
                return optional(model('user')->find($value))->name ?? $value;
            }
            elseif (str($key)->is('*_at') && !empty($value)) {
                return format($value, 'datetime')->value();
            }
            else return $value;
        })->toArray();
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
    }

    // create audit entry
    public function createAuditEntry($data) : void
    {
        $this->audits()->create(array_merge(
            $this->getAuditData(data_get($data, 'event')),
            $data,
        ));
    }
}