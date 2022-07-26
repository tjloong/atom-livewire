<?php

namespace Jiannius\Atom\Http\Livewire\App\Label\Update;

use Livewire\Component;

class Children extends Component
{
    public $label;
    public $form;
    public $locales;

    /**
     * Validation rules
     */
    protected function rules()
    {
        $rules = [
            'form.slug' => 'nullable',
        ];

        foreach ($this->locales as $locale) {
            $rules['form.name.'.$locale] = 'required';
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
            $messages['form.name.'.$locale.'.required'] = __('Child label name is required.');
        }

        return $messages;
    }

    /**
     * Mount
     */
    public function mount()
    {
        //
    }

    /**
     * Create
     */
    public function create()
    {
        $this->form = [
            'name' => $this->locales->mapWithKeys(fn($locale) => [$locale => null])->all(),
            'slug' => null,
            'type' => $this->label->type,
            'parent_id' => $this->label->id,
        ];

        $this->dispatchBrowserEvent('child-form-modal-open');
    }

    /**
     * Edit
     */
    public function edit($id)
    {
        $child = $this->label->children()->find($id);
        $this->form = $child->toArray();
        $this->dispatchBrowserEvent('child-form-modal-open');
    }

    /**
     * Sort children
     */
    public function sortChildren($data = null)
    {
        foreach ($data as $index => $id) {
            $this->label->children()->where('id', $id)->update(['seq' => $index + 1]);
        }

        $this->dispatchBrowserEvent('toast', ['message' => __('Label Children Sorted'), 'type' => 'success']);
    }

    /**
     * Submit
     */
    public function submit()
    {
        $this->resetValidation();
        $this->validate();

        $child = data_get($this->form, 'id')
            ? $this->label->children()->find(data_get($this->form, 'id'))
            : model('label');

        $child->fill($this->form)->save();

        $this->dispatchBrowserEvent('child-form-modal-close');
    }

    /**
     * Delete
     */
    public function delete($id)
    {
        $child = $this->label->children()->find($id);
        $child->delete();

        $this->dispatchBrowserEvent('toast', ['message' => __('Label Child Deleted')]);
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.label.update.children');
    }
}