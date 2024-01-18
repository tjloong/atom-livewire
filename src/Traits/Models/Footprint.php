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

    // get footprint
    public function footprint($name = null) : mixed
    {
        if ($name) {
            $split = explode('.', $name);
            $event = $split[0];
            $attr = count($split) === 2 ? $split[1] : null;

            if (in_array($attr, ['timestamp', 'at', 'datetime', 'date'])) {
                $ts = $this->{$event.'_at'} ?? data_get($this->footprint, $event.'.timestamp');

                if ($ts instanceof Carbon) return $ts;
                else return $ts ? new Carbon($ts) : null;
            }
            elseif (in_array($attr, ['description', 'caption'])) {
                return tr('app.label.footprint-'.$event, [
                    'user' => $this->footprint($event.'.name'),
                    'timestamp' => format($this->footprint($event.'.timestamp'), 'datetime')->value(),
                ]);
            }
            elseif ($attr) {
                $id = $this->{$event.'_by'} ?? data_get($this->footprint, $event.'.id');
                $user = $id ? model('user')->find($id) : null;

                if ($attr === 'user') return $user;
                elseif ($val = data_get($this->footprint, $event.'.'.$attr)) return $val;
                elseif ($user) return data_get($user, $attr);
                else return null;
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
        $footprint[$event] = [
            'id' => $id,
            'name' => $name,
            'timestamp' => $ts,
        ];

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