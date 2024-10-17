<?php

namespace Jiannius\Atom\Macros;

use Illuminate\Support\Facades\DB;

class Builder
{
    public function toPage()
    {
        return function ($page = 1, $rows = 50) {
            return $this->paginate($rows, ['*'], 'page', $page);
        };
    }

    public function tableColumns()
    {
        return function () {
            $table = $this->getModel()->getTable();
            $columns = cache()->remember('table_'.$table.'_columns', now()->addDays(7), fn() => DB::select("show columns from `$table`"));

            return collect($columns)->map(fn($val) => [
                'name' => data_get($val, 'Field'),
                'type' => data_get($val, 'Type'),
            ])->values();
        };
    }

    public function tableHasColumn()
    {
        return function ($column) {
            $columns = $this->getModel()->tableColumns();
            return $columns->where('name', $column)->count() > 0;
        };
    }

    public function tableColumnType()
    {
        return function ($column, $checker = null) {
            $columns = $this->tableColumns();
            $column = $columns->firstWhere('name', $column);
            $type = data_get($column, 'type');
    
            return $checker ? in_array($type, (array) $checker) : $type;
        };
    }

    public function filter()
    {
        return function ($filters) {
            $table = $this->getModel()->getTable();
            $filters = $this->parseFilters($filters);

            foreach ($filters as $filter) {
                $value = $filter['value'];

                if ($scope = $filter['scope'] ?? null) {
                    $this->$scope($value);
                }
                else {
                    $column = $filter['column'];
                    $operator = $filter['operator'] ?? null;
                    $function = $filter['function'] ?? null;
                    $json = $filter['json'] ?? false;

                    if ($json) $column = str($column)->replace('.', '->')->toString();
                    
                    if ($function) $column = DB::raw($function.'('.$table.'.'.$column.')');
                    else $column = $table.'.'.$column;

                    if ($operator) $this->where($column, $operator, $value);
                    else if (is_array($value) && $value) {
                        if ($json) $this->whereJsonContains($column, $value);
                        else $this->whereIn($column, $value);
                    }
                    else if (!is_array($value)) {
                        $this->where($column, $value);
                    }
                }
            }

            return $this;
        };
    }

    public function parseFilters()
    {
        return function ($filters) {
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
                            $function = $this->tableColumnType($column, 'date') ? 'date' : null;
                            array_push($parsed, ['column' => $column, 'value' => $from, 'operator' => '>=', 'function' => $function]);
                            array_push($parsed, ['column' => $column, 'value' => $to, 'operator' => '<=', 'function' => $function]);
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
        };
    }
}