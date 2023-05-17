<?php

namespace Jiannius\Atom\Http\Livewire\App\Label;

use Jiannius\Atom\Traits\Livewire\WithForm;
use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class Form extends Component
{
    use WithForm;
    use WithPopupNotify;

    public $label;
    public $inputs;

    /**
     * Validation
     */
    protected function validation(): array
    {
        return array_merge(
            [
                'label.type' => ['nullable'],
                'label.slug' => ['nullable'],
                'label.parent_id' => ['nullable'],
            ],

            $this->locales->mapWithKeys(fn($locale, $key) => [
                'inputs.name.'.$locale => ['required' => 'Label name ('.$locale.') is required.'],
            ])->toArray(),
        );
    }

    /**
     * Mount
     */
    public function mount()
    {
        $this->fill([
            'inputs.name' => (array)$this->label->name,
        ]);
    }

    /**
     * Get locales property
     */
    public function getLocalesProperty(): mixed
    {
        return collect(config('atom.locales'));
    }

    /**
     * Get parent trails property
     */
    public function getParentTrailsProperty(): array
    {
        $trails = collect();
        $parent = $this->label->parent;

        while ($parent) {
            $trails->push($parent->locale('name'));
            $parent = $parent->parent;
        }

        return $trails->reverse()->values()->all();
    }

    /**
     * Submit
     */
    public function submit(): void
    {
        $this->validateForm();

        $this->label->fill([
            'name' => data_get($this->inputs, 'name'),
            'slug' => null,
        ])->save();

        $this->emit('submitted');
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.label.form');
    }
}