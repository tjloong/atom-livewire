<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

function has_table($name)
{
    $tables = cache()->rememberForever('database_tables', function() {
        return collect(DB::select('show tables'))
            ->map(fn($val) => collect($val)->values()->all())
            ->collapse()
            ->values()
            ->all();
    });

    return in_array($name, $tables);
}

function has_column($table, $column)
{
    return Schema::hasColumn($table, $column);
}