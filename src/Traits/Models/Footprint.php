<?php

namespace Jiannius\Atom\Traits\Models;

use Carbon\Carbon;

trait Footprint
{
    // boot
    protected static function bootFootprint() : void
    {
        static::created(function($model) {
            $model->setFootprint('created')->saveQuietly();
        });

        static::updated(function($model) {
            $model->setFootprint('updated')->saveQuietly();
        });

        static::deleted(function($model) {
            if ($model->exists) $model->setFootprint('trashed')->saveQuietly();
        });
    }

    // initialize
    protected function initializeFootprint() : void
    {
        $this->casts['footprint'] = 'array';
    }

    // scope for where footprint
    public function scopeWhereFootprint($query, ...$parameters) : void
    {
        $table = $this->getTable();
        $key = $this->getFootprintQueryKey(data_get($parameters, 0));

        if (count($parameters) === 3) {
            $operator = data_get($parameters, 1);
            $value = data_get($parameters, 2);
        }
        else {
            $operator = null;
            $value = data_get($parameters, 1);
        }

        $query->where($table.'.'.$key, $operator ?? '=', $value);
    }

    // scope for or where footprint
    public function scopeOrWhereFootprint($query, $key, $operator, $value = null) : void
    {
        $query->orWhere(fn($q) => $q->whereFootprint($query, $key, $operator, $value));
    }

    // scope for where in footprint
    public function scopeWhereInFootprint($query, $key, $value) : void
    {
        $table = $this->getTable();
        $key = $this->getFootprintQueryKey($key);

        $query->whereIn($table.'.'.$key, (array) $value);
    }

    // scope for where not in footprint
    public function scopeWhereNotInFootprint($query, $key, $value) : void
    {
        $table = $this->getTable();
        $key = $this->getFootprintQueryKey($key);

        $query->whereNotIn($table.'.'.$key, (array) $value);
    }

    // get footprint
    public function footprint($name = null) : mixed
    {
        if ($name) {
            $split = explode('.', $name);
            $event = $split[0];
            $attr = count($split) > 1 ? $split[1] : null;
            $userAttr = count($split) > 2 ? $split[2] : null;

            if (in_array($attr, ['timestamp', 'at', 'datetime', 'date'])) {
                $ts = $this->{$event.'_at'} ?? data_get($this->footprint, $event.'.timestamp');

                if ($ts instanceof Carbon) return $ts;
                else return $ts ? new Carbon($ts) : null;
            }
            elseif (in_array($attr, ['description', 'caption'])) {
                return tr('app.label.footprint-'.$event, [
                    'user' => $this->footprint($event.'.name') ?? '?',
                    'timestamp' => format($this->footprint($event.'.timestamp'), 'datetime')->value(),
                ]);
            }
            elseif ($attr === 'user') {
                $id = $this->{$event.'_by'} ?? data_get($this->footprint, $event.'.id');
                $user = $id ? model('user')->find($id) : null;

                if ($userAttr) return data_get($user, $userAttr);
                else return $user;
            }
            elseif ($attr) {
                return data_get($this->footprint, $event.'.'.$attr);
            }

            return data_get($this->footprint, $event);
        }

        return $this->footprint;
    }

    // get footprint date column - eg. closed_at - date column is optional for footprint to work
    public function getFootprintDateColumn($event) : mixed
    {
        $col = $event.'_at';

        return $this->hasColumn($col) ? $col : null;
    }

    // get footprint user column - eg. closed_by - user column is optional for footprint to work
    public function getFootprintUserColumn($event) : mixed
    {
        $col = $event.'_by';

        return $this->hasColumn($col) ? $col : null;
    }

    // get footprint query key
    public function getFootprintQueryKey($key) : string
    {
        return str($key)->start('footprint.')->replace('.', '->')->toString();
    }

    // check has footprint
    public function hasFootprint($event) : bool
    {
        $ts = $this->footprint(str($event)->finish('.timestamp'));

        return $ts && $ts->lessThan(now());
    }

    // set footprint
    public function setFootprint($event) : mixed
    {
        $datecol = $this->getFootprintDateColumn($event);
        $usercol = $this->getFootprintUserColumn($event);
        $id = user('id');
        $name = user('name');
        $ts = now();

        if ($datecol && $this->$datecol) $ts = $this->$datecol;

        $footprint = $this->footprint ?? [];
        $footprint[$event] = array_filter([
            'id' => $id,
            'name' => $name,
            'timestamp' => $ts,
        ]);

        $this->fill(['footprint' => $footprint]);

        if ($datecol && !$this->$datecol) $this->fill([$datecol => $ts]);
        if ($usercol) $this->fill([$usercol => $id]);

        return $this;
    }

    // erase footprint
    public function eraseFootprint($event) : mixed
    {
        $datecol = $this->getFootprintDateColumn($event);
        $usercol = $this->getFootprintUserColumn($event);

        $footprint = $this->footprint ?? [];
        unset($footprint[$event]);

        $this->fill(['footprint' => $footprint]);

        if ($datecol) $this->fill([$datecol => null]);
        if ($usercol) $this->fill([$usercol => null]);

        return $this;
    }
}