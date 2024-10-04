<?php

namespace Jiannius\Atom;

use Jiannius\Atom\Traits\Livewire\WithPopupNotify;

class Component extends \Livewire\Component
{
    use WithPopupNotify;

    public $errors;
    public $keynonce;

    public $table = [
        'sort' => ['column' => null, 'direction' => null],
        'max' => 100,
        'archived' => false,
        'trashed' => false,
        'checkboxes' => [],
    ];

    // mount
    public function mount()
    {
        //
    }

    // get paginate
    public function getTable($query, $max = null, $sort = null, $filters = null)
    {
        if ($column = get($this->table, 'sort.column')) {
            $direction = get($this->table, 'sort.direction') ?? 'asc';
            $query = $query->orderBy($column, $direction);
        }
        else if ($sort) $sort($query);
        else $query = $query->latest();

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
        if ($filters = $filters ?? $this->filters ?? []) $query = $query->filter($filters);

        $max = $max ?? get($this->table, 'max');

        return $query->paginate($max);
    }

    public function wirekey($prefix = null) : string
    {
        if ($this->keynonce) return $prefix.'-'.$this->keynonce;
        else {
            $this->refreshKeynonce();
            return $this->wirekey($prefix);
        }
    }

    // refresh key nonce
    public function refreshKeynonce() : void
    {
        $this->keynonce = uniqid();
    }

    // TODO: deprecate
    // open/close modal
    public function modal($open = true, $id = null)
    {
        $this->refreshKeynonce();

        $id = $id ?? $this->getName() ?? $this->id;

        if ($open) $this->dispatchBrowserEvent('open-modal', $id);
        else $this->dispatchBrowserEvent('close-modal', $id);
    }

    // open/close overlay
    public function overlay($open = true, $id = null)
    {
        $this->refreshKeynonce();

        $id = $id ?? $this->getName() ?? $this->id;

        $this->dispatchBrowserEvent('overlay', [
            'id' => $id,
            'open' => $open,
        ]);
    }

    // get view
    public function getView() : string
    {
        $class = static::class;

        $path = str($class)
            ->replaceFirst('Jiannius\Atom\Http\\', '')
            ->replaceFirst('App\Http\\', '')
            ->split('/\\\/')
            ->map(fn($s) => str()->kebab($s))
            ->join('.');
        
        return view()->exists($path) ? $path : 'atom::'.$path;
    }

    // get view data
    public function getViewData() : array
    {
        return [];
    }

    // get layout
    public function getLayout() : string
    {
        $path = 'layouts.'.request()->portal();

        return view()->exists($path) ? $path : '';
    }

    // render
    public function render()
    {
        // expose error bag so front end can use
        $this->errors = collect($this->getErrorBag()->toArray())->map(fn($e) => head($e))->toArray();

        $layout = $this->getLayout();
        $view = $this->getView();
        $data = $this->getViewData();

        return empty($layout)
            ? view($view, $data)
            : view($view, $data)->layout($layout);
    }
}