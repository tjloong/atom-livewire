<?php

namespace Jiannius\Atom\Traits\Livewire;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\WithPagination;

trait WithTable
{
    use WithPagination;
    
    public $maxRows = 100;
    public $checkboxes = [];

    /**
     * Initialize the trait
     */
    protected function initializeWithTable(): void
    {
        $this->queryString = array_merge(
            $this->queryString, 
            ['page' => ['except' => 1]],
            isset($this->sort) ? ['sort' => ['except' => $this->sort]] : [],
            isset($this->filters) ? ['filters' => ['except' => $this->filters]] : [],
        );
    }

    /**
     * Get sort by property
     */
    public function getSortByProperty(): string
    {
        if (!$this->sort) return null;

        $split = explode(',', $this->sort);

        return $split[0];
    }

    /**
     * Get sort order property
     */
    public function getSortOrderProperty(): string
    {
        if (!$this->sort) return null;

        $split = explode(',', $this->sort);

        return $split[1] ?? 'asc';
    }

    /**
     * Get paginator property
     */
    public function getPaginatorProperty(): LengthAwarePaginator
    {
        if (!$this->query) return null;

        if (!empty($this->sort) && ($this->query instanceof Builder)) {
            $this->query->orderBy($this->sortBy, $this->sortOrder);
            return $this->query->paginate($this->maxRows);
        }
        else return $this->query;
    }

    /**
     * Get table property
     */
    public function getTableProperty(): array
    {
        return $this->paginator
            ->through(fn($query) => $this->getTableColumns($query) ?: $query)
            ->items();
    }

    /**
     * Updated filters
     */
    public function updatedFilters(): void
    {
        $this->resetPage();
        $this->resetCheckboxes();
    }

    /**
     * Get table columns
     */
    public function getTableColumns($query): array
    {
        return [];
    }
    
    /**
     * Toggle checkbox
     */
    // public function toggleCheckbox($value): void
    // {
    //     $values = collect($this->checkboxes);

    //     if (in_array($value, ['*', '**'])) {
    //         if (in_array($values->first(), ['*', '**'])) $values = collect();
    //         else $values = collect([$value]);
    //     }
    //     else {
    //         $values = $values->reject('*')->reject('**');
    //         $values = $values->contains($value)
    //             ? $values->reject($value)
    //             : $values->concat([$value]);
    //     }

    //     $this->fill(['checkboxes' => $values->values()->all()]);
    // }

    /**
     * Reset table checkboxes
     */
    public function resetCheckboxes(): void
    {
        $this->checkboxes = [];
    }

    /**
     * Reset sort
     */
    public function resetSort(): void
    {
        $this->reset('sort');
    }
}