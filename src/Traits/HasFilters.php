<?php

namespace Jiannius\Atom\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

trait HasFilters
{
    /**
     * Scope for status
     */
    public function scopeStatus($query, $status)
    {
        if ($status === 'active' && $this->hasColumn('is_active')) return $query->where('is_active', true);
        if ($status === 'inactive' && $this->hasColumn('is_active')) return $query->where('is_active', false);

        return $query->whereIn('status', (array)$status);
    }

    /**
     * Apply scope from filters
     *
     * @param Builder $query
     * @param array $filters
     * @return Builder
     */
    public function scopeFilter($query, $filters)
    {
        foreach ($this->parseFilters($filters) as $filter) {
            $column = $filter['column'];
            $value = $filter['value'];
            $scope = $filter['scope'] ?? null;
            $operator = $filter['operator'] ?? null;

            if ($scope) $query->$scope($value);
            else if ($operator) $query->where($column, $operator, $value);
            else if (is_array($value)) $query->whereIn($column, $value);
            else $query->where($column, $value);
        }

        return $query;
    }

    /**
     * Check model has a specific column
     * 
     * @return boolean
     */
    public function hasColumn($column)
    {
        $table = $this->getTable();

        return ($this->connection && Schema::connection($this->connection)->hasColumn($table, $column))
                    || (!$this->connection && Schema::hasColumn($table, $column));
    }

    /**
     * Check model column is a date type
     * 
     * @return boolean
     */
    public function isDateColumn($column)
    {
        return $this->hasColumn($column)
            && Schema::getColumnType($this->getTable(), $column) === 'date';
    }

    /**
     * Check model column is a datetime type
     * 
     * @return boolean
     */
    public function isDatetimeColumn($column)
    {
        return $this->hasColumn($column)
            && in_array(Schema::getColumnType($this->getTable(), $column), ['datetime', 'timestamp']);
    }

    /**
     * Parse filters array
     * 
     * @return array
     */
    public function parseFilters($filters)
    {
        $parsed = [];

        foreach ($filters as $key => $value) {
            if (empty($value)) continue;

            $column = preg_replace('/^(from_|to_)/', '', $key);
            $fn = str()->camel($column);

            if (method_exists($this, 'scope'.ucfirst($fn))) {
                array_push($parsed, ['column' => $column, 'value' => $value, 'scope' => $fn]);
            }
            else {
                if ($this->isDateColumn($column) || $this->isDatetimeColumn($column)) {
                    if (str($key)->is('from_*')) {
                        if (Carbon::hasFormat($value, 'Y-m-d')) $value = Carbon::createFromFormat('Y-m-d', $value)->startOfDay();
                        if (Carbon::hasFormat($value, 'Y-m-d H:i:s')) $value = Carbon::createFromFormat('Y-m-d H:i:s', $value);

                        array_push($parsed, ['column' => $column, 'value' => $value->toDatetimeString(), 'operator' => '>=']);
                    }

                    if (str($key)->is('to_*')) {
                        if (Carbon::hasFormat($value, 'Y-m-d')) $value = Carbon::createFromFormat('Y-m-d', $value)->endOfDay();
                        if (Carbon::hasFormat($value, 'Y-m-d H:i:s')) $value = Carbon::createFromFormat('Y-m-d H:i:s', $value);

                        array_push($parsed, ['column' => $column, 'value' => $value->toDatetimeString(), 'operator' => '<=']);
                    }
                }
                else if ($this->hasColumn($column)) {
                    array_push($parsed, ['column' => $column, 'value' => $value]);
                }
            }
        }

        return $parsed;
    }
}