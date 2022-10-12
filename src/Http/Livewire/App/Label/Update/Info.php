<?php

namespace Jiannius\Atom\Http\Livewire\App\Label\Update;

use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class Info extends Component
{
    use WithPopupNotify;
    
    public $types;
    public $label;
    public $names;
    public $locales;

    /**
     * Validation rules
     */
    protected function rules()
    {
        $rules = [
            'label.name' => 'required',
            'label.type' => 'required',
            'label.slug' => 'nullable',
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
        $messages = [
            'label.type.required' => __('Label type is required.'),
        ];

        foreach ($this->locales as $locale) {
            $messages['names.'.$locale.'.required'] = __('Label name is required.');
        }

        return $messages;
    }

    /**
     * Mount
     */
    public function mount()
    {
        $this->names = (array)$this->label->name;
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

        if ($this->label->wasRecentlyCreated) return redirect()->route('app.settings', ['labels']);
        else $this->popup('Label Updated');
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.label.update.info');
    }
}