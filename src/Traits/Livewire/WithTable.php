<?php

namespace Jiannius\Atom\Traits\Livewire;

use Livewire\WithPagination;

trait WithTable
{
    use WithPagination;
    
    public $maxRows = 100;
    public $checkboxes = [];

    /**
     * Initialize the trait
     * 
     * @return void
     */
    protected function initializeWithTable()
    {
        $this->queryString = array_merge(
            $this->queryString, 
            ['page' => ['except' => 1]],
            isset($this->sort) ? ['sort' => ['except' => $this->sort]] : [],
        );
    }

    /**
     * Get sort by property
     */
    public function getSortByProperty()
    {
        if (!$this->sort) return;

        $split = explode(',', $this->sort);

        return $split[0];
    }

    /**
     * Get sort order property
     */
    public function getSortOrderProperty()
    {
        if (!$this->sort) return;

        $split = explode(',', $this->sort);

        return $split[1] ?? 'asc';
    }

    /**
     * Get paginator property
     */
    public function getPaginatorProperty()
    {
        if (!$this->query) return;

        if (!empty($this->sort)) $this->query->orderBy($this->sortBy, $this->sortOrder);

        return $this->query
            ->paginate($this->maxRows)
            ->through(fn($query) => $this->getTableColumns($query));
    }

    /**
     * Updated filters
     */
    public function updatedFilters()
    {
        $this->resetPage();
        $this->resetCheckboxes();
    }

    /**
     * Get table columns
     */
    public function getTableColumns($query)
    {
        return $query;
    }
    
    /**
     * Toggle checkbox
     */
    public function toggleCheckbox($value)
    {
        $values = collect($this->checkboxes);

        if (in_array($value, ['*', '**'])) {
            if (in_array($values->first(), ['*', '**'])) $values = collect();
            else $values = collect([$value]);
        }
        else {
            $values = $values->reject('*')->reject('**');
            $values = $values->contains($value)
                ? $values->reject($value)
                : $values->concat([$value]);
        }

        $this->fill(['checkboxes' => $values->values()->all()]);
    }

    /**
     * Reset table checkboxes
     */
    public function resetCheckboxes()
    {
        $this->checkboxes = [];
    }
}