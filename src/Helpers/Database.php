<?php

use Illuminate\Support\Facades\DB;

/**
 * Check db has table
 */
function has_table($name)
{
    $name = str()->plural($name);
    $tables = get_tables();

    return in_array($name, $tables);
}

/**
 * Check db has column
 */
function has_column($table, $column)
{
    $table = str()->plural($table);

    if (!has_table($table)) return false;

    $columns = get_table_columns($table);

    return collect($columns)->where('name', $column)->count() > 0;
}

/**
 * Get column type
 */
function get_column_type($table, $column)
{
    $table = str()->plural($table);

    if (!has_table($table)) return false;
    if (!has_column($table, $column)) return false;

    $columns = get_table_columns($table);
    
    return data_get(collect($columns)->firstWhere('name', $column), 'type');
}

/**
 * Get tables from db
 */
function get_tables()
{
    return cache()->rememberForever('database_tables', function() {
        return collect(DB::select('show tables'))
            ->map(fn($val) => collect($val)->values()->all())
            ->collapse()
            ->values()
            ->all();
    });
}

/**
 * Get columns from table
 */
function get_table_columns($table)
{
    return cache()->rememberForever('database_columns_'.$table, function () use ($table) {
        return collect(DB::select('show columns from '.$table))
            ->map(fn($val) => [
                'name' => data_get($val, 'Field'),
                'type' => data_get($val, 'Type'),
            ])
            ->values()
            ->all();
    });
}