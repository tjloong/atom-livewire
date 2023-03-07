<?php

namespace Jiannius\Atom\Http\Livewire\App\Label;

use Illuminate\Support\Collection;
use Jiannius\Atom\Traits\Livewire\WithForm;
use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class FormModal extends Component
{
    use WithForm;
    use WithPopupNotify;

    public $label;
    public $names;

    protected $listeners = ['open'];

    /**
     * Validation
     */
    protected function validation(): array
    {
        $rules = [
            'label.type' => ['nullable'],
            'label.slug' => ['nullable'],
            'label.parent_id' => ['nullable'],
        ];

        foreach ($this->locales as $locale) {
            $rules['names.'.$locale] = ['required' => 'Label name ('.$locale.') is required.'];
        }

        return $rules;
    }

    /**
     * Get locales property
     */
    public function getLocalesProperty(): Collection
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
     * Open
     */
    public function open($data): void
    {
        $this->label = data_get($data, 'id')
            ? model('label')->readable()->findOrFail(data_get($data, 'id'))
            : model('label')->fill($data);

        $this->names = (array)$this->label->name;

        $this->dispatchBrowserEvent('label-form-modal-open');
    }

    /**
     * Submit
     */
    public function submit(): void
    {
        $this->validateForm();

        $this->label->fill([
            'name' => $this->names,
            'slug' => null,
        ])->save();

        $this->emit('refresh');
        $this->dispatchBrowserEvent('label-form-modal-close');
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.label.form-modal');
    }
}