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

        $query = (clone $this->query);

        if ($this->tableSortOrder) {
            $order = explode(',', $this->tableSortOrder);
            $query->orderBy($order[0], $order[1] ?? 'asc');
        }

        if (has_column($query->getModel()->getTable(), 'archived_at')) {
            if ($this->showArchived) $query->whereNotNull('archived_at');
            else $query->whereNull('archived_at');
        }

        if (has_column($query->getModel()->getTable(), 'deleted_at')) {
            if ($this->showTrashed) $query->onlyTrashed();
        }

        return $query->paginate($this->tableMaxRows);
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

        if ($this->showArchived) {
            $query->whereNotNull('archived_at')->get()->each(fn($row) => 
                $row->eraseFootprint('archived')->save()
            );
        }
        elseif ($this->showTrashed) {
            $query->onlyTrashed()->restore();
        }

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
        $this->popup('app.label.trashed');
    }

    // empty trashed
    public function emptyTrashed($id = []) : void
    {
        (clone $this->query)->onlyTrashed()
            ->when($id, fn($q) => $q->whereIn($this->query->getModel()->getTable().'.id', $id))
            ->forceDelete();

        $this->fill(['showTrashed' => false]);
        $this->popup('app.label.trash-cleared');
    }

    // move to archived
    public function archive($id) : void
    {
        (clone $this->query)
            ->whereIn($this->query->getModel()->getTable().'.id', $id)
            ->get()
            ->each(fn($row) => $row->setFootprint('archived')->save());

        $this->reset('checkboxes');
        $this->popup('app.label.archived');
    }

    // restore all archived
    public function restoreArchived() : void
    {
        (clone $this->query)->whereNotNull('archived_at')->each(fn($row) => 
            $row->eraseFootprint('archived')->save()
        );
    }
}