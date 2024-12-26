<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;
    use HasUlids;

    protected $guarded = [];

    public function getStringAttribute()
    {
        return $this->__toString();
    }

    public function __toString()
    {
        $lines = collect([$this->line_1, $this->line_2, $this->line_3])->filter()->join(', ');
        $city = collect([$this->postcode, $this->city])->filter()->join(' ');
        $country = (string) str($this->country)->lower()->headline();
        $string = collect([$lines, $city, $this->state, $country])->join(', ');

        return collect(explode(',', $string))
            ->map(fn ($s) => trim($s))
            ->unique()
            ->filter()
            ->join(', ');
    }
}
