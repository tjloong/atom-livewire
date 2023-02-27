<?php

namespace Jiannius\Atom\Http\Livewire\App\Label;

use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class FormModal extends Component
{
    use WithPopupNotify;

    public $label;
    public $names;

    protected $listeners = ['open'];

    /**
     * Validation rules
     */
    protected function rules()
    {
        $rules = [
            'label.type' => 'nullable',
            'label.slug' => 'nullable',
            'label.parent_id' => 'nullable',
        ];

        foreach ($this->locales as $locale) {
            $rules['names.'.$locale] = 'required';
        }

        return $rules;
    }

    /**
     * Validation messages
     */
    protected function messages()
    {
        $messages = [];

        foreach ($this->locales as $locale) {
            $messages['names.'.$locale.'.required'] = __('Label name ('.$locale.') is required.');
        }

        return $messages;
    }

    /**
     * Get locales property
     */
    public function getLocalesProperty()
    {
        return collect(config('atom.locales'));
    }

    /**
     * Get parent trails property
     */
    public function getParentTrailsProperty()
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
    public function open($data)
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
    public function submit()
    {
        $this->resetValidation();
        $this->validate();

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
    public function render()
    {
        return atom_view('app.label.form-modal');
    }
}