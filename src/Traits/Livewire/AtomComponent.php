<?php

namespace Jiannius\Atom\Traits\Livewire;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Jiannius\Atom\Atom;
use Livewire\WithPagination;

trait AtomComponent
{
    use AuthorizesRequests;
    use WithPagination;

    public $errors;
    public $keyhash;

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
        return [...$this->listeners ?? [], 'execute'];
    }

    public function getFormProperty()
    {
        $form = ['required' => []];
    
        if (!$this->rules()) return $form;

        $form['required'] = collect($this->rules())
            ->mapWithKeys(fn($rules, $key) => [
                $key => collect($rules)
                    ->filter(fn($val) => is_string($val) && str($val)->startsWith('required'))
                    ->count() > 0,
            ])
            ->filter(fn($val) => $val === true)
            ->all();

        return $form;
    }

    public function updated($attr, $value)
    {
        // sanitize empty string value to null
        if (is_string($value) && trim($value) === '') {
            $split = collect(explode('.', $attr));

            if ($split->count() > 1) {
                $key = $split->first();
                $field = $split->forget(0)->join('.');

                if ($this->$key instanceof \Illuminate\Database\Eloquent\Model) {
                    $this->$key->fill([$field => null]);
                }
                else {
                    data_set($key, $field, null);
                }
            }
            else {
                $this->fill([$attr => null]);
            }
        }
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

    public function componentName()
    {
        $class = self::class;

        $class = (string) str($class)
            ->replaceFirst('App\Livewire\\', '')
            ->replaceFirst('App\Http\Livewire\\', '')
            ->replaceFirst('Jiannius\Atom\Livewire\\', '')
            ->replaceFirst('Jiannius\Atom\Http\Livewire\\', '');

        return collect(explode('\\', $class))
            ->map(fn ($val) => (string) str($val)->snake()->slug())
            ->join('.');
    }

    public function sheet($name = null)
    {
        return Atom::sheet($name ?? $this->componentName());
    }

    public function modal($name = null)
    {
        return Atom::modal($name ?? $this->componentName());
    }

    public function action($name, $params = null)
    {
        return Atom::action($name, $params);
    }

    public function alert($alert, $type = 'info')
    {
        return Atom::alert($alert, $type);
    }

    public function toast($toast, $type = 'info')
    {
        return Atom::toast($toast, $type);
    }

    // fill properties into other component
    public function fillTo($component, $props)
    {
        $this->commandTo($component, 'fill', ['values' => $props]);
    }

    // command to other component to run method
    public function commandTo($component, $method, $props = [])
    {
        $data = [
            'method' => $method,
            'props' => $props,
        ];

        if ($component === 'parent') $this->emitUp('execute', $data);
        else $this->emitTo($component, 'execute', $data);
    }

    // execute an method
    public function execute($props)
    {
        $method = get($props, 'method');
        $props = get($props, 'props');
        $this->$method(...$props);
    }

    // refresh component
    public function refresh()
    {
        $this->resetValidation();
        $this->renewWirekey();
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

    public function getViewConfiguration()
    {
        $class = static::class;

        $viewPath = str($class)
            ->replaceFirst('Jiannius\Atom\\', '')
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

        $view = $this->getViewConfiguration();
        $viewName = get($view, 'name');
        $viewData = get($view, 'data', []);
        $layout = get($view, 'layout');
        $layoutData = get($view, 'layout_data', []);

        return empty($layout)
            ? view($viewName, $viewData)
            : view($viewName, $viewData)->layout($layout, $layoutData);
    }
}