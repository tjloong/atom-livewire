<?php

namespace Jiannius\Atom\Macros;

class Carbon
{
    public function local()
    {
        return function () {
            $tz = optional(user())->settings('timezone') ?? config('atom.timezone');
            return $tz ? $this->timezone($tz) : $this;
        };
    }

    public function pretty()
    {
        return function ($option = null) {
            $option = $option ?? 'date';

            if ($option === 'date') $format = 'd M Y';
            elseif ($option === 'datetime') $format = 'd M Y g:iA';
            elseif ($option === 'datetime-24') $format = 'd M Y H:i:s';
            elseif ($option === 'time') $format = 'g:i A';
            elseif ($option === 'time-24') $format = 'H:i:s';
            else $format = $option;

            return $this->local()->format($format);
        };
    }

    public function recent()
    {
        return function ($days = 1) {
            if ($this->isToday()) return $this->pretty('time');
            if ($this->gte(now()->subDays($days))) return $this->local()->fromNow();

            return $this->pretty('datetime');
        };
    }
}