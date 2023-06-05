<?php

use Illuminate\Support\Facades\Schema;

function has_table($name)
{
    return Schema::hasTable($name);
}

function has_column($table, $column)
{
    return Schema::hasColumn($table, $column);
}