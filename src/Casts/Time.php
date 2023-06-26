<?php
 
namespace Jiannius\Atom\Casts;

use Illuminate\Support\Carbon;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
 
class Time implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     * @return array<string, mixed>
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if (!$value) return $value;

        $carbon = Carbon::createFromFormat('Y-m-d H:i:s', '1970-01-01 '.$value);

        return $carbon->format('h:i A');
    }
 
    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        $str = str($value)
            ->replaceLast(' AM', 'AM')
            ->replaceLast(' am', 'am')
            ->replaceLast(' PM', 'PM')
            ->replaceLast(' pm', 'pm');

        if ($str->is('*AM') || $str->is('*am') || $str->is('*PM') || $str->is('*pm')) {
            $carbon = Carbon::createFromFormat('Y-m-d h:iA', '1970-01-01 '.$str->toString());
            return $carbon->format('H:i:s');
        }

        return $value;
    }
}