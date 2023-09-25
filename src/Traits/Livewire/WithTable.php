<?php

namespace Jiannius\Atom\Traits\Livewire;

use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\WithPagination;

trait WithTable
{
    use WithPopupNotify;
    use WithPagination;
    
    public $maxRows = 100;
    public $checkboxes = [];
    public $showTrashed = false;
    public $showArchived = false;

    // get sort by property
    public function getSortByProperty() : string
    {
        if (!$this->sort) return null;

        $split = explode(',', $this->sort);

        return $split[0];
    }

    // get sort order property
    public function getSortOrderProperty() : string
    {
        if (!$this->sort) return null;

        $split = explode(',', $this->sort);

        return $split[1] ?? 'asc';
    }

    // get paginator property
    public function getPaginatorProperty() : mixed
    {
        if (!$this->query) return null;

        if ($this->query instanceof LengthAwarePaginator) return $this->query;

        if (!empty($this->sort)) $this->query->orderBy($this->sortBy, $this->sortOrder);
        if ($this->showArchived) $this->query->onlyArchived();
        if ($this->showTrashed) $this->query->onlyTrashed();

        return $this->query->paginate($this->maxRows);
    }

    // get table property
    public function getTableProperty() : array
    {
        return $this->paginator
            ->through(fn($query) => $this->getTableColumns($query) ?: $query)
            ->items();
    }

    // updated filters
    public function updatedFilters() : void
    {
        $this->reset([
            'page',
            'checkboxes',
        ]);
    }

    // get table columns
    public function getTableColumns($query) : array
    {
        return [];
    }

    // reset sort
    public function resetSort() : void
    {
        $this->reset('sort');
    }

    // reset filters
    public function resetFilters() : void
    {
        $this->reset('filters');
    }

    // select checkbox
    public function selectCheckbox($val) : void
    {
        $checkboxes = collect($this->checkboxes);
        
        if ($checkboxes->contains($val)) $checkboxes = $checkboxes->reject($val);
        else $checkboxes->push($val);

        $this->checkboxes = $checkboxes->values()->all();
    }

    // restore
    public function restore($id) : void
    {
        $query = (clone $this->query)->whereIn($this->query->getModel()->getTable().'.id', $id);

        if ($this->showArchived) $query->onlyArchived()->get()->each(fn($row) => $row->markArchived(false));
        elseif ($this->showTrashed) $query->onlyTrashed()->restore();

        $this->reset([
            'showArchived',
            'showTrashed',
            'checkboxes',
        ]);
    }

    // move to trashed
    public function trash($id) : void
    {
        (clone $this->query)
            ->whereIn($this->query->getModel()->getTable().'.id', $id)
            ->delete();

        $this->reset('checkboxes');
        $this->popup('Moved to Trashed.');
    }

    // empty trashed
    public function emptyTrashed($id = []) : void
    {
        (clone $this->query)->onlyTrashed()
            ->when($id, fn($q) => $q->whereIn($this->query->getModel()->getTable().'.id', $id))
            ->forceDelete();

        $this->fill(['showTrashed' => false]);
        $this->popup('Trashed Cleared.');
    }

    // move to archived
    public function archive($id) : void
    {
        (clone $this->query)
            ->whereIn($this->query->getModel()->getTable().'.id', $id)
            ->get()
            ->each(fn($row) => $row->markArchived());

        $this->reset('checkboxes');
        $this->popup('Moved to Archived.');
    }

    // restore archived
    public function restoreArchived($id = []) : void
    {
        (clone $this->query)->onlyArchived()
            ->when($id, fn($q) => $q->whereIn($this->query->getModel()->getTable().'.id', $id))
            ->get()
            ->each(fn($row) => $row->markArchived(false));

        $this->fill(['showArchived' => false]);
        $this->popup('Archived Restored.');
    }
}