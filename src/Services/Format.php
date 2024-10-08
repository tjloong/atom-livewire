<?php

namespace Jiannius\Atom\Services;

use Illuminate\Support\Number;

class Format
{
    // constructor
    public function __construct(
        public $value,
        public $options = null,
    ) {
        //
    }

    // to string
    public function __toString()
    {
        return $this->value('');
    }

    // shorthand for value
    public function val($default = null) : mixed
    {
        return $this->value($default);
    }

    // get value
    public function value($default = null) : mixed
    {
        return $this->date()
            ?? $this->period()
            ?? $this->currency()
            ?? $this->excerpt()
            ?? $this->value
            ?? $default;
    }

    // carbon
    public function carbon($value = null) : mixed
    {
        $value = $value ?? $this->value;

        if ($value instanceof \Carbon\Carbon) $carbon = $value;
        else {
            if (validator(['value' => $value], ['value' => 'date'])->fails()) return null;
            $carbon = \Carbon\Carbon::parse($value);
        }

        if ($tz = optional(user())->settings('timezone') ?? config('atom.timezone')) {
            $carbon->timezone($tz);
        }

        return $carbon;
    }

    // date
    public function date() : mixed
    {
        if ($carbon = $this->carbon()) {
            if ($this->options === 'human') return $carbon->diffForHumans();
            if ($this->options === 'datetime') return $carbon->format('d M Y g:iA');
            if ($this->options === 'datetime-24') return $carbon->format('d M Y H:i:s');
            if ($this->options === 'time') return $carbon->format('g:i A');
            if ($this->options === 'time-24') return $carbon->format('H:i:s');
            if ($this->options) return $carbon->format($this->options);

            return $carbon->format('d M Y');
        }

        return null;
    }

    // period
    public function period() : mixed
    {
        if (is_array($this->value) && !empty(array_filter($this->value))) {
            $from = $this->carbon($this->value[0]);
            $to = $this->carbon($this->value[1]);

            if ($from && !$to) {
                $val = format($from, $this->options)->value().' - ∞';
            }
            else if (!$from && $to) {
                $val = '∞ - '.format($to, $this->options)->value();
            }
            else if ($from->isSameDay($to)) {
                $val = format($from)->value();
                if ($this->options === 'datetime') $val .= ' '.format($from, 'time')->value().' - '.format($to, 'time')->value();
            }
            else if ($from->isSameMonth($to) && $from->isSameYear($to) && $this->options !== 'datetime') {
                $val = $from->day.' - '.format($to)->value();
            }
            else if ($this->options === 'datetime') {
                $val = format($from, 'datetime')->value().' - '.format($to, 'datetime')->value();
            }
            else {
                $val = format($from)->value().' - '.format($to)->value();
            }

            return $val;
        }

        return null;
    }

    // currency
    public function currency() : mixed
    {
        if (!is_numeric($this->value)) return null;

        $symbol = is_string($this->options) ? $this->options : data_get($this->options, 'symbol');
        $rounding = data_get($this->options, 'rounding', false);
        $bracket = data_get($this->options, 'bracket', false);
        $amount = $rounding ? (round((float) $this->value * 2, 1)/2) : $this->value;
        $value = $symbol ? ($symbol.' '.Number::format($amount, 2)) : Number::format($amount, 2);

        return ($bracket && $this->value < 0) ? '('.str($value)->replaceFirst('-', '').')' : $value;
    }

    // short number
    public function short() : mixed
    {
        if (!is_numeric($this->value)) return null;

        if ($this->value > 999999999) $val = round(($this->value/1000000000), 2).'B';
        else if ($this->value > 999999) $val = round(($this->value/1000000), 2).'M';
        else if ($this->value > 999) $val = round(($this->value/1000), 2).'K';
        else $val = $this->value;

        return is_string($this->options)
            ? $this->options.' '.(string) $val
            : (string) $val;
    }

    // file size
    public function filesize($initUnit) : mixed
    {
        if (!is_numeric($this->value)) return $this->value;

        $n = $this->value;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $index = array_search($initUnit, $units);
    
        while ($n > 1024) {
            $n = $n/1024;
            $index = $index + 1;
        }
    
        return round($n, 2).' '.$units[$index];    
    }

    // address
    public function address() : mixed
    {
        $l1 = preg_replace('/,$/im', '', data_get($this->value, 'address_1') ?? data_get($this->value, 'addr_1'));
        $l2 = preg_replace('/,$/im', '', data_get($this->value, 'address_2') ?? data_get($this->value, 'addr_2'));

        $zip = data_get($this->value, 'zip') ?? data_get($this->value, 'postcode');
        $city = data_get($this->value, 'city');
        $l3 = collect([$zip, $city])->filter()->join(' ');

        $state = data_get($this->value, 'state');
        $country = data_get($this->value, 'country');
        $country = data_get(countries($country), 'name');
        $l4 = collect([$state, $country])->filter()->join(' ');

        $address = collect([$l1, $l2, $l3, $l4])->filter()->join(', ');
    
        return empty($address) ? null : $address;
    }

    // excerpt
    public function excerpt($len = null) : mixed
    {
        if (is_string($this->value)) {
            $len = $len ?? $this->options ?? 80;

            return str(strip_tags($this->value))->words($len)->trim()->toString();
        }

        return null;
    }

    // email - format ['abc@test.com' => 'John'] to John <abc@test.com> 
    public function email() : mixed
    {
        if (is_array($this->value)) {
            $emails = collect($this->value)->map(function($val, $key) {
                $email = is_numeric($key) ? $val : $key;
                $name = is_numeric($key) ? null : $val;
                return $name ? ($name.' <'.$email.'>') : $email;
            })->values();

            return $emails->count() === 1 ? $emails->first() : $emails->all();
        }

        return null;
    }

    // abbreviation
    public function abbr($len = 2) : mixed
    {
        if (is_string($this->value)) {
            $splits = str($this->value)->slug()->split('/-/');
            $splits = $splits->count() === 1 ? collect(mb_str_split($splits->first())) : $splits;
            $abbr = $splits->map(fn($val) => str($val)->upper()->charAt(0))->join('');

            return $len ? str($abbr)->substr(0, $len) : $abbr;
        }

        return null;
    }
}