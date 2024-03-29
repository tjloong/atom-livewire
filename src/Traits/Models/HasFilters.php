<?php

namespace Jiannius\Atom\Traits\Models;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

trait HasFilters
{
    // model boot
    protected static function bootHasFilters() : void
    {
        static::saving(function ($model) {
            $model->sanitizeNumericColumns();
        });
    }

    // scope for readable
    public function scopeReadable($query, $data = null) : void
    {
        //
    }

    // scope for status
    public function scopeStatus($query, $status) : void
    {
        if ($status === 'trashed') $query->onlyTrashed();
        else if ($status === 'active' && $this->tableHasColumn('is_active')) $query->where('is_active', true);
        else if ($status === 'inactive' && $this->tableHasColumn('is_active')) $query->where('is_active', false);
        else if ($status) {
            $status = collect($status)
                ->map(fn($val) => is_string($val) ? $val : $val->value)
                ->toArray();

            $query->whereIn('status', $status);
        }
    }

    // apply scope from filters
    public function scopeFilter($query, $filters) : void
    {
        $table = $this->getTable();
        $filters = $this->parseFilters($filters);

        foreach ($filters as $filter) {
            $value = $filter['value'];

            if ($scope = $filter['scope'] ?? null) {
                $query->$scope($value);
            }
            else {
                $column = $filter['column'];
                $operator = $filter['operator'] ?? null;
                $function = $filter['function'] ?? null;
                $json = $filter['json'] ?? false;

                if ($json) $column = str($column)->replace('.', '->')->toString();
                
                if ($function) $column = DB::raw($function.'('.$table.'.'.$column.')');
                else $column = $table.'.'.$column;

                if ($operator) $query->where($column, $operator, $value);
                else if (is_array($value) && $value) {
                    if ($json) $query->whereJsonContains($column, $value);
                    else $query->whereIn($column, $value);
                }
                else if (!is_array($value)) {
                    $query->where($column, $value);
                }
            }
        }
    }

    // scope for paginatetopage
    public function scopeToPage($query, $page = 1, $rows = 50) : LengthAwarePaginator
    {
        return $query->paginate($rows, ['*'], 'page', $page);
    }

    // get table columns
    public function tableColumns() : mixed
    {
        $table = $this->getTable();

        return collect(DB::select("show columns from `$table`"))->map(fn($val) => [
            'name' => data_get($val, 'Field'),
            'type' => data_get($val, 'Type'),
        ])->values();
    }

    // get table column type
    public function tableColumnType($column, $checker = null) : mixed
    {
        $columns = $this->tableColumns();
        $column = $columns->firstWhere('name', $column);
        $type = data_get($column, 'type');

        return $checker ? in_array($type, (array) $checker) : $type;
    }

    // check table has column
    public function tableHasColumn($column) : bool
    {
        return Schema::hasColumn($this->getTable(), $column);
    }

    // parse filters array
    public function parseFilters($filters) : array
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
                if ($this->tableColumnType($column, ['date', 'datetime', 'timestamp'])) {
                    if (str($value)->is('* to *')) {
                        $from = head(explode(' to ', $value));
                        $to = last(explode(' to ', $value));
                        array_push($parsed, ['column' => $column, 'value' => $from, 'operator' => '>=', 'function' => 'date']);
                        array_push($parsed, ['column' => $column, 'value' => $to, 'operator' => '<=', 'function' => 'date']);
                    }
                    else {
                        if (str($key)->is('from_*')) {
                            $value = format($value)->carbon()->startOfDay()->utc();
                            array_push($parsed, ['column' => $column, 'value' => $value->toDatetimeString(), 'operator' => '>=']);
                        }
    
                        if (str($key)->is('to_*')) {
                            $value = format($value)->carbon()->endOfDay()->utc();
                            array_push($parsed, ['column' => $column, 'value' => $value->toDatetimeString(), 'operator' => '<=']);
                        }
                    }
                }
                else if ($this->tableColumnType($column, 'json')) {
                    array_push($parsed, ['column' => $column, 'value' => $value, 'json' => true]);
                }
                else if ($this->tableHasColumn($column)) {
                    array_push($parsed, ['column' => $column, 'value' => $value]);
                }
            }
        }

        return $parsed;
    }

    // sanitize numeric columns
    public function sanitizeNumericColumns()
    {
        $columns = $this->tableColumns()->filter(function ($col) {
            $type = data_get($col, 'type');
            $str = str($type);

            return $str->is('decimal*') 
                || $str->is('double*') 
                || $str->is('int*') 
                || $str->is('bigint*');
        })->map(fn($col) => data_get($col, 'name'))->values()->all();

        foreach ($columns as $col) {
            if (empty($this->$col)) $this->fill([$col => null]);
        }
    }

    // get value in json column
    public function getJson($key) : mixed
    {
        return get($this, $key);
    }
}