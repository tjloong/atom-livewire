<?php

namespace Jiannius\Atom\Traits\Livewire;

use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\WithPagination;

trait WithTable
{
    use WithPagination;
    
    public $maxRows = 100;
    public $checkboxes = [];

    // get sort by property
    public function getSortByProperty(): string
    {
        if (!$this->sort) return null;

        $split = explode(',', $this->sort);

        return $split[0];
    }

    // get sort order property
    public function getSortOrderProperty(): string
    {
        if (!$this->sort) return null;

        $split = explode(',', $this->sort);

        return $split[1] ?? 'asc';
    }

    // get paginator property
    public function getPaginatorProperty(): mixed
    {
        if (!$this->query) return null;

        if ($this->query instanceof LengthAwarePaginator) return $this->query;

        if (!empty($this->sort)) $this->query->orderBy($this->sortBy, $this->sortOrder);

        return $this->query->paginate($this->maxRows);
    }

    // get table property
    public function getTableProperty(): array
    {
        return $this->paginator
            ->through(fn($query) => $this->getTableColumns($query) ?: $query)
            ->items();
    }

    // updated filters
    public function updatedFilters(): void
    {
        $this->resetPage();
        $this->resetCheckboxes();
    }

    // get table columns
    public function getTableColumns($query): array
    {
        return [];
    }

    // reset table checkboxes
    public function resetCheckboxes(): void
    {
        $this->checkboxes = [];
    }

    // reset sort
    public function resetSort(): void
    {
        $this->reset('sort');
    }
}