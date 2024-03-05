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
            ?? $this->currency()
            ?? $this->excerpt()
            ?? $this->value
            ?? $default;
    }

    // carbon
    public function carbon() : mixed
    {
        if ($this->value instanceof \Carbon\Carbon) $carbon = $this->value;
        else {
            if (validator(['value' => $this->value], ['value' => 'date'])->fails()) return null;
            $carbon = \Carbon\Carbon::parse($this->value);
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

    // currency
    public function currency() : mixed
    {
        if (is_numeric($this->value)) {
            $symbol = is_string($this->options) ? $this->options : data_get($this->options, 'symbol');
            $rounding = data_get($this->options, 'rounding', false);
            $bracket = data_get($this->options, 'bracket', false);
            $amount = $rounding ? (round((float) $this->value * 2, 1)/2) : $this->value;
            $value = $symbol ? ($symbol.' '.Number::format($amount, 2)) : Number::format($amount, 2);
    
            return ($bracket && $this->value < 0) ? '('.str($value)->replaceFirst('-', '').')' : $value;
        }

        return null;
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