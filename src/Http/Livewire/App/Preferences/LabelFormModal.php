<?php

namespace Jiannius\Atom\Http\Livewire\App\Preferences;

use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class LabelFormModal extends Component
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
     * Open
     */
    public function open($data)
    {
        $this->label = data_get($data, 'id')
            ? model('label')
                ->when(model('label')->enabledBelongsToAccountTrait, fn($q) => $q->belongsToAccount())
                ->findOrFail(data_get($data, 'id'))
            : model('label')->fill([
                'type' => data_get($data, 'type'),
                'parent_id' => data_get($data, 'parent_id'),
            ]);

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
        ])->save();

        $this->emitUp('refresh');
        $this->dispatchBrowserEvent('label-form-modal-close');
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.preferences.label-form-modal');
    }
}