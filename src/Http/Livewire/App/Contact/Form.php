<?php

namespace Jiannius\Atom\Http\Livewire\App\Contact;

use Jiannius\Atom\Traits\Livewire\WithFile;
use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class Form extends Component
{
    use WithFile;
    use WithPopupNotify;

    public $fields;
    public $contact;

    /**
     * Validation rules
     */
    protected function rules()
    {
        $rules = [
            'contact.category' => 'required',
            'contact.type' => 'required',
            'contact.name' => 'required',
            'contact.email' => 'nullable',
            'contact.phone' => 'nullable',
            'contact.fax' => 'nullable',
            'contact.brn' => 'nullable',
            'contact.tax_number' => 'nullable',
            'contact.website' => 'nullable',
            'contact.address_1' => 'nullable',
            'contact.address_2' => 'nullable',
            'contact.city' => 'nullable',
            'contact.zip' => 'nullable',
            'contact.state' => 'nullable',
            'contact.country' => 'nullable',
            'contact.avatar_id' => 'nullable',
            'contact.owned_by' => 'nullable',
        ];

        foreach ($this->fields as $i => $field) {
            $fieldrules = [];

            if (data_get($field, 'type') === 'multiple') {
                $fieldrules[] = 'array';    
                if ($min = data_get($field, 'min')) $fieldrules[] = 'min:'.$min;
                if ($max = data_get($field, 'max')) $fieldrules[] = 'max:'.$max;
            }
            
            if (data_get($field, 'required', false)) $fieldrules[] = 'required';

            if ($fieldrules) $rules['fields.'.$i.'.value'] = $fieldrules;
        }

        return $rules;
    }

    /**
     * Validation messages
     */
    protected function messages()
    {
        return [
            'contact.category.required' => 'Contact category is required.',
            'contact.type.required' => 'Contact type is required.',
            'contact.name.required' => 'Contact name is required.',
            'fields.*.value.required' => 'This field is required.',
            'fields.*.value.min' => 'This field must have at least 1 item.',
            'fields.*.value.max' => 'This field must not have more than 2 items.',
        ];
    }

    /**
     * Mount
     */
    public function mount()
    {
        $this->contact->fill([
            'data' => $this->contact->data ?? [
                'fields' => []
            ],
        ]);

        $this->setFields();
        $this->loadFieldsValue();
    }

    /**
     * Set fields
     */
    public function setFields()
    {
        $this->fields = [];
    }

    /**
     * Load fields value
     */
    public function loadFieldsValue()
    {
        $datafields = collect($this->contact->data->fields)->map(fn($val) => (array)$val);

        // get the value from contact data fields
        $this->fields = collect($this->fields)->map(function($field) use ($datafields) {
            $data = $datafields->first(fn($val) => 
                (isset($val['uuid']) && data_get($val, 'uuid') === data_get($field, 'uuid'))
                || (!isset($val['uuid']) && data_get($val, 'label') === data_get($field, 'label'))
            );

            $value = data_get($data, 'value');

            if (is_null($value) && in_array(data_get($field, 'type'), ['dropdown', 'multiple'])) {
                $value = [];
            }

            return array_merge((array)$field, ['value' => $value]);
        });

        // if contact data got fields not defined in settings, put that in
        $datafields->each(function($data) {
            if (
                (isset($data['uuid']) && !$this->fields->firstWhere('uuid', data_get($data, 'uuid')))
                || (!isset($data['uuid']) && !$this->fields->firstWhere('label', data_get($data, 'label')))
            ) {
                $this->fields->push($data);
            }
        });
    }

    /**
     * Submit
     */
    public function submit()
    {
        $this->resetValidation();
        $this->validate();

        $this->persist();

        return redirect()->route('app.contact.view', [$this->contact->id]);
    }

    /**
     * Persist
     */
    public function persist()
    {
        $this->contact->fill([
            'data' => ['fields' => $this->fields],
        ])->save();
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.contact.form');
    }
}