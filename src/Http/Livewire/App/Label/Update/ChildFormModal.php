<?php

namespace Jiannius\Atom\Http\Livewire\App\Label\Update;

use Livewire\Component;

class ChildFormModal extends Component
{
    public $parent;
    public $child;
    public $locales;

    protected $listeners = ['open'];

    /**
     * Validation rules
     */
    public function rules()
    {
        $rules = [
            'child.slug' => 'nullable',
        ];

        foreach ($this->locales as $locale) {
            $rules['child.name.'.$locale] = 'required';
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
            $messages['child.name.'.$locale.'.required'] = __('Child label name is required.');
        }

        return $messages;
    }

    /**
     * Open
     */
    public function open($id = null)
    {
        if ($id) $child = model('label')->find($id);
        else {
            $child = model('label')->fill([
                'type' => $this->parent->type,
                'parent_id' => $this->parent->id,
            ]);

            if (model('label')->enabledBelongsToAccountTrait) $child->fill(['account_id' => $this->parent->account_id]);
        }

        $this->child = $child->toArray();

        $this->dispatchBrowserEvent('child-form-modal-open');
    }

    /**
     * Submit
     */
    public function submit()
    {
        $this->resetValidation();
        $this->validate();

        model('label')->updateOrCreate(
            ['id' => data_get($this->child, 'id')],
            $this->child
        );

        $this->dispatchBrowserEvent('child-form-modal-close');
        $this->emitUp('childUpdated');
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.label.update.child-form-modal');
    }
}