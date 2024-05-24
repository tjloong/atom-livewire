<?php

namespace Jiannius\Atom\Traits\Livewire;

use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\WithPagination;

trait WithTable
{
    use WithPagination;

    public $tableOrderBy;
    public $tableOrderDesc;
    public $tableShowTrashed;
    public $tableShowArchived;

    public $tableMaxRows = 100;
    public $tableCheckboxes = [];

    // get paginator property
    public function getPaginatorProperty() : mixed
    {
        if (!$this->query) return null;
        if ($this->query instanceof LengthAwarePaginator) return $this->query;

        $query = (clone $this->query);

        if ($this->tableOrderBy) {
            $query->orderBy($this->tableOrderBy, $this->tableOrderDesc ? 'desc' : 'asc');
        }

        if ($query->getModel()->tableHasColumn('archived_at')) {
            if ($this->tableShowArchived) $query->whereNotNull('archived_at');
            else $query->whereNull('archived_at');
        }

        if ($query->getModel()->tableHasColumn('deleted_at') && $this->tableShowTrashed) $query->onlyTrashed();
        else if ($filters = $this->getTableFilters()) $query->filter($filters);

        return $query->paginate($this->tableMaxRows);
    }

    // get is filters empty
    public function getIsFiltersEmptyProperty() : bool
    {
        return !$this->filters || empty(collect($this->filter)->reject(fn($val) =>
            is_null($val)
            || (is_array($val) && !count($val))
        ));
    }

    // updated filters
    public function updatedFilters() : void
    {
        $this->resetPage();
        $this->reset('tableCheckboxes');
    }

    // set table max rows
    public function setTableMaxRows($n) : void
    {
        $this->fill(['tableMaxRows' => $n]);
    }

    // get table trashed count
    public function getTableTrashedCount() : int
    {
        return (clone $this->query)->onlyTrashed()->count();
    }

    // get table filters
    public function getTableFilters() : mixed
    {
        return $this->filters ?? null;
    }

    // reset filters
    public function resetFilters() : void
    {
        $this->resetPage();
        $this->reset('filters');
        $this->reset('tableCheckboxes');
    }

    // select checkbox
    public function selectTableCheckbox($val) : void
    {
        $checkboxes = collect($this->checkboxes);
        
        if ($checkboxes->contains($val)) $checkboxes = $checkboxes->reject($val);
        else $checkboxes->push($val);

        $this->checkboxes = $checkboxes->values()->all();
    }

    // restore
    public function restoreTableRows() : void
    {
        $query = (clone $this->query)
            ->when($this->tableCheckboxes, fn($q) => $q->whereIn($this->query->getModel()->getTable().'.id', $this->tableCheckboxes));

        if ($this->tableShowArchived) {
            $query->whereNotNull('archived_at')->get()->each(fn($row) => 
                $row->eraseFootprint('archived')->save()
            );
        }
        elseif ($this->tableShowTrashed) {
            $query->onlyTrashed()->restore();
        }

        $this->reset([
            'tableShowArchived',
            'tableShowTrashed',
            'tableCheckboxes',
        ]);
    }

    // move to trashed
    public function trashTableRows() : void
    {
        (clone $this->query)
            ->whereIn($this->query->getModel()->getTable().'.id', $this->tableCheckboxes)
            ->delete();

        $this->reset('tableCheckboxes');
        $this->popup('app.label.trashed');
    }

    // move to archived
    public function archiveTableRows() : void
    {
        (clone $this->query)
            ->whereIn($this->query->getModel()->getTable().'.id', $this->tableCheckboxes)
            ->get()
            ->each(fn($row) => $row->setFootprint('archived')->save());

        $this->reset('tableCheckboxes');
        $this->popup('app.label.archived');
    }

    // delete
    public function deleteTableRows() : void
    {
        (clone $this->query)
            ->whereIn($this->query->getModel()->getTable().'.id', $this->tableCheckboxes)
            ->delete();

        $this->reset('tableCheckboxes');
        $this->popup('app.alert.deleted');
    }

    // empty trashed
    public function emptyTrashedTableRows() : void
    {
        (clone $this->query)->onlyTrashed()
            ->when($this->tableCheckboxes, fn($q) => $q->whereIn($this->query->getModel()->getTable().'.id', $this->tableCheckboxes))
            ->forceDelete();

            $this->reset([
                'tableShowArchived',
                'tableShowTrashed',
                'tableCheckboxes',
            ]);

            $this->popup('app.alert.trash-cleared');
    }
}