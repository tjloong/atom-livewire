<?php

namespace Jiannius\Atom\Traits\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

trait HasFilters
{
    /**
     * Scope for readable
     */
    public function scopeReadable($query, $data = null): void
    {
        //
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
    public function scopeToPage($query, $page = 1, $rows = 50): void
    {
        $query->paginate($rows, ['*'], 'page', $page);
    }

    /**
     * Scope for visibility
     */
    public function scopeVisible($query, $user = null)
    {
        $user = $user ?? user();
        
        if ($user->isTier('root') || $user->isRole('admin') || $user->visibility === 'global') {
            if ($this->enabledHasTenantTrait) return $query->where('tenant_id', $user->tenant_id);
            else return $query;
        }

        if ($user->visibility === 'restrict') {
            return $query->where(fn($q) => $q
                ->where('created_by', $user->id)
                ->when(
                    Schema::hasColumn($this->getTable(), 'owned_by'), 
                    fn($q) => $q->orWhere('owned_by', $user->id)
                )
            );
        }
        else if ($user->visibility === 'team') {
            if (!enabled_module('teams')) return $query;
            
            $teamId = $user->teams->pluck('id')->toArray();

            return $query->where(fn($q) => $q
                ->whereHas('createdBy', fn($q) => $q->inTeam($teamId))
                ->when(
                    Schema::hasColumn($this->getTable(), 'owned_by'), 
                    fn($q) => $q->orWhereHas('ownedBy', fn($q) => $q->inTeam($teamId))
                )
            );
        }
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
}