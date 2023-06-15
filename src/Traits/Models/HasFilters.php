<?php

namespace Jiannius\Atom\Traits\Models;

use Illuminate\Pagination\LengthAwarePaginator;

trait HasFilters
{
    /**
     * Model boot
     */
    protected static function bootHasFilters(): void
    {
        static::saving(function ($model) {
            $model->sanitizeNumericColumns();
        });
    }

    /**
     * Scope for readable
     */
    public function scopeReadable($query, $data = null): void
    {
        if ($this->usesHasTenant) $query->forTenant();
    }

    /**
     * Scope for status
     */
    public function scopeStatus($query, $status): void
    {
        if ($status === 'trashed') $query->onlyTrashed();
        else if ($status === 'active' && $this->hasColumn('is_active')) $query->where('is_active', true);
        else if ($status === 'inactive' && $this->hasColumn('is_active')) $query->where('is_active', false);
        else $query->whereIn('status', (array)$status);
    }

    /**
     * Apply scope from filters
     */
    public function scopeFilter($query, $filters): void
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
    }

    /**
     * Scope for paginateToPage
     */
    public function scopeToPage($query, $page = 1, $rows = 50): LengthAwarePaginator
    {
        return $query->paginate($rows, ['*'], 'page', $page);
    }

    /**
     * Check model has a specific column
     */
    public function hasColumn($column): bool
    {
        return has_column($this->getTable(), $column);
    }

    /**
     * Check model column is a date type
     */
    public function isDateColumn($column): bool
    {
        return get_column_type($this->getTable(), $column) === 'date';
    }

    /**
     * Check model column is a datetime type
     */
    public function isDatetimeColumn($column): bool
    {
        return in_array(
            get_column_type($this->getTable(), $column),
            ['datetime', 'timestamp'],
        );
    }

    /**
     * Parse filters array
     */
    public function parseFilters($filters): array
    {
        $parsed = [];

        foreach (($filters ?? []) as $key => $value) {
            if (is_null($value)) continue;

            $column = preg_replace('/^(from_|to_)/', '', $key);
            $fn = str()->camel($column);

            if ($this->hasNamedScope($fn)) {
                array_push($parsed, ['column' => $column, 'value' => $value, 'scope' => $fn]);
            }
            else {
                if ($this->isDateColumn($column) || $this->isDatetimeColumn($column)) {
                    if (str($key)->is('from_*')) {
                        $value = format_date($value, 'carbon')->startOfDay()->utc();

                        // if (Carbon::hasFormat($value, 'Y-m-d')) $value = Carbon::createFromFormat('Y-m-d', $value)->startOfDay();
                        // if (Carbon::hasFormat($value, 'Y-m-d H:i:s')) $value = Carbon::createFromFormat('Y-m-d H:i:s', $value);

                        array_push($parsed, ['column' => $column, 'value' => $value->toDatetimeString(), 'operator' => '>=']);
                    }

                    if (str($key)->is('to_*')) {
                        $value = format_date($value, 'carbon')->endOfDay()->utc();

                        // if (Carbon::hasFormat($value, 'Y-m-d')) $value = Carbon::createFromFormat('Y-m-d', $value)->endOfDay();
                        // if (Carbon::hasFormat($value, 'Y-m-d H:i:s')) $value = Carbon::createFromFormat('Y-m-d H:i:s', $value);

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

    /**
     * Sanitize numeric columns
     */
    public function sanitizeNumericColumns()
    {
        $columns = collect(get_table_columns($this->getTable()))->filter(function ($col) {
            $type = data_get($col, 'type');
            $str = str($type);

            return $str->is('decimal*') 
                || $str->is('double*') 
                || $str->is('int*') 
                || $str->is('bigint*');
        })->map(fn($col) => data_get($col, 'name'))->values()->all();

        foreach ($columns as $col) {
            if (empty($this->$col)) $this->$col = null;
        }
    }
}