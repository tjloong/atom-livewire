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

        if ($query->getModel()->tableHasColumn('deleted_at')) {
            if ($this->tableShowTrashed) $query->onlyTrashed();
        }

        return $query->paginate($this->tableMaxRows);
    }

    // updated filters
    public function updatedFilters() : void
    {
        $this->reset('page');
        $this->reset('tableCheckboxes');
    }

    // set table max rows
    public function setTableMaxRows($n) : void
    {
        $this->fill(['tableMaxRows' => $n]);
    }

    // reset filters
    public function resetFilters() : void
    {
        $this->reset('page');
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
    public function restoreTableRows($id) : void
    {
        $query = (clone $this->query)->whereIn($this->query->getModel()->getTable().'.id', $id);

        if ($this->showArchived) {
            $query->whereNotNull('archived_at')->get()->each(fn($row) => 
                $row->eraseFootprint('archived')->save()
            );
        }
        elseif ($this->showTrashed) {
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
        $this->popup('app.label.deleted');
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

            $this->popup('app.label.trash-cleared');
    }
}