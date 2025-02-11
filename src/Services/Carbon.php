<?php

namespace Jiannius\Atom\Services;

class Carbon extends \Carbon\CarbonImmutable
{
    public function local()
    {
        $tz = optional(user())->settings('timezone') ?? config('atom.timezone');

        return $tz ? $this->timezone($tz) : $this;
    }

    public function pretty ($option = null)
    {
        $option = $option ?? 'date';

        if ($option === 'date') $format = 'd M Y';
        elseif ($option === 'datetime') $format = 'd M Y g:iA';
        elseif ($option === 'datetime-24') $format = 'd M Y H:i:s';
        elseif ($option === 'time') $format = 'g:i A';
        elseif ($option === 'time-24') $format = 'H:i:s';
        else $format = $option;

        return $this->local()->format($format);
    }

    public function recent ($days = 1)
    {
        if ($this->isToday()) return $this->pretty('time');
        if ($this->gte(now()->subDays($days))) return $this->local()->fromNow();

        return $this->pretty('datetime');
    }

    public static function parseRange($range)
    {
        $range = $range ?? '1970-01-01 00:00:00 to '.now()->toDateTimeString();

        $from = head(explode('to', $range));
        $from = carbon($from ?: '1970-01-01 00:00:00');

        $to = last(explode('to', $range));
        $to = $to ? carbon($to) : now();

        $diff = [
            'd' => $from->diffInDays($to),
            'm' => $from->diffInMonths($to),
            'y' => $from->diffInYears($to),
        ];

        $past = get($diff, 'd') > 0 ? [
            'from' => $from->copy()->subDays(get($diff, 'd')),
            'to' => $to->copy()->subDays(get($diff, 'd')),
        ] : null;

        $tz = now()->timezone(
            optional(user())->settings('timezone') ?? config('atom.timezone')
        )->format('P');

        return [
            'range' => $range,
            'from' => $from,
            'to' => $to,
            'diff' => $diff,
            'past' => $past,
            'tz' => $tz,
        ];
    }
}
