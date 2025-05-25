<?php

namespace Jiannius\Atom\Traits\Livewire;

use Livewire\WithPagination;

trait AtomPaginator
{
    use WithPagination;

    public $table = [
        'sort' => ['column' => null, 'direction' => null],
        'max' => 100,
        'archived' => false,
        'trashed' => false,
        'checkboxes' => [],
    ];

    public function getTable($query, $max = null, $sort = null, $filters = null)
    {
        if ($column = get($this->table, 'sort.column')) {
            $direction = get($this->table, 'sort.direction') ?? 'asc';
            $query = $query->orderBy($column, $direction);
        }
        else if ($sort) $sort($query);
        else $query = $query->latest();

        if (method_exists($query, 'getModel')) {
            $model = (clone $query)->getModel();
            $archived = get($this->table, 'archived');
            $trashed = get($this->table, 'trashed');

            if ($model->tableHasColumn('archived_at')) {
                $query = $query->when($archived,
                    fn($q) => $q->whereNotNull('archived_at'),
                    fn($q) => $q->whereNull('archived_at')
                );
            }

            if ($model->tableHasColumn('deleted_at') && $trashed) $query = $query->onlyTrashed();
        }

        if ($filters = $filters ?? $this->filters ?? []) $query = $query->filter($filters);

        $max = $max ?? get($this->table, 'max');

        return $query->paginate($max);
    }
}