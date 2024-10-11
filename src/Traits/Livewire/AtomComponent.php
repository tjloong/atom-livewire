<?php

namespace Jiannius\Atom\Traits\Livewire;

use Jiannius\Atom\Atom;

trait AtomComponent
{
    public $errors;
    public $keyhash;

    public $form = [
        'required' => [],
    ];

    public $table = [
        'sort' => ['column' => null, 'direction' => null],
        'max' => 100,
        'archived' => false,
        'trashed' => false,
        'checkboxes' => [],
    ];

    public $options = [];

    // validation rules
    protected function rules() : array
    {
        if (!method_exists($this, 'validation')) return [];

        return collect($this->validation())->mapWithKeys(fn($props, $field) => [
            $field => collect($props)
                ->map(fn($val, $key) => is_string($key) ? $key : $val)
                ->values()
                ->all() ?: ['nullable'],
        ])->toArray();
    }

    // validation messages
    protected function messages() : array
    {
        if (!method_exists($this, 'validation')) return [];

        $messages = [];

        collect($this->validation())->each(function($rules, $field) use (&$messages) {
            foreach ((array)$rules as $rule => $message) {
                if (is_string($rule) && $rule !== 'nullable') {
                    if (str($rule)->is('*:*')) $rule = head(explode(':', $rule));
                    $messages[$field.'.'.$rule] = $message;
                }
            }
        })->toArray();

        return $messages;
    }

    protected function getListeners()
    {
        return $this->listeners + ['execute'];
    }

    // mount
    public function mountAtomComponent()
    {
        $this->setForm();
    }

    public function updated($name, $value)
    {
        if (is_string($value) && trim($value) === '') $this->fill([$name => null]);
    }

    // set form
    public function setForm()
    {
        if (!$this->rules()) return;

        $this->fill([
            'form.required' => collect($this->rules())
                ->mapWithKeys(fn($rules, $key) => [
                    $key => collect($rules)
                        ->filter(fn($val) => is_string($val) && str($val)->startsWith('required'))
                        ->count() > 0,
                ])
                ->filter(fn($val) => $val === true)
                ->all(),
        ]);
    }

    // get table
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

    // get options
    public function getOptions($id, $name, $filters = [])
    {
        $this->options[$id] = Atom::options($name, $filters);
    }

    // ask other component to execute action
    public function ask($component, $action, ...$args)
    {
        if ($component === 'parent') {
            $this->emitUp('execute', [
                'action' => $action,
                'args' => $args,
            ]);
        }
        else {
            $this->emitTo($component, 'execute', [
                'action' => $action,
                'args' => $args,
            ]);
        }
    }

    // execute an action with arguments
    public function execute($args)
    {
        $action = get($args, 'action');
        unset($args['action']);

        $args = get($args, 'args');

        $this->$action(...$args);
    }

    // refresh component
    public function refresh()
    {
        $this->emit('$refresh');
    }

    // generate wire key
    public function wirekey($prefix = null)
    {
        if ($this->keyhash) return $prefix.'-'.$this->keyhash;
        else {
            $this->renewWirekey();
            return $this->wirekey($prefix);
        }
    }

    // renew wire key
    public function renewWirekey()
    {
        $this->keyhash = uniqid();
    }

    // get view configuration
    public function view()
    {
        $class = static::class;

        $viewPath = str($class)
            ->replaceFirst('Jiannius\Atom\Http\\', '')
            ->replaceFirst('App\Http\\', '')
            ->split('/\\\/')
            ->map(fn($s) => str()->kebab($s))
            ->join('.');

        $layoutPath = 'layouts.'.request()->portal();

        return [
            'name' => view()->exists($viewPath) ? $viewPath : 'atom::'.$viewPath,
            'layout' => view()->exists($layoutPath) ? $layoutPath : '',
        ];
    }

    public function render()
    {
        // expose error bag so front end can use
        $this->errors = collect($this->getErrorBag()->toArray())->map(fn($e) => head($e))->toArray();

        $view = $this->view();
        $name = get($view, 'name');
        $layout = get($view, 'layout');
        $data = get($view, 'data', []);

        return empty($layout)
            ? view($name, $data)
            : view($name, $data)->layout($layout);
    }
}