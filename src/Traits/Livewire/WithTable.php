<?php

namespace Jiannius\Atom\Traits\Livewire;

use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\WithPagination;

trait WithTable
{
    use WithPagination;

    public $tableSortOrder;
    public $tableMaxRows = 100;
    public $checkboxes = [];
    public $showTrashed = false;
    public $showArchived = false;

    // get paginator property
    public function getPaginatorProperty() : mixed
    {
        if (!$this->query) return null;

        if ($this->query instanceof LengthAwarePaginator) return $this->query;

        if ($this->tableSortOrder) {
            $order = explode(',', $this->tableSortOrder);
            $this->query->orderBy($order[0], $order[1] ?? 'asc');
        }

        if ($this->showArchived) $this->query->onlyArchived();
        if ($this->showTrashed) $this->query->onlyTrashed();

        return $this->query->paginate($this->tableMaxRows);
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

    // reset table sort order
    public function resetTableSortOrder() : void
    {
        $this->reset('tableSortOrder');
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