<?php

namespace Jiannius\Atom\Traits\Livewire;

trait WithCustomFields
{
    public $customFieldInputs;

    /**
     * Get custom fields property
     */
    public function getCustomFieldsProperty()
    {
        return [];
    }

    /**
     * Get custom fields form
     */
    public function getCustomFieldsForm()
    {
        if (!$this->customFields) return [];

        return collect($this->customFields)->mapWithKeys(function ($field, $i) {
            $rules = [];
            $input = data_get($field, 'type');
            $label = data_get($field, 'label');

            if (data_get($field, 'required')) {
                $rules['required'] = $label.' is required.';
            }

            if ($input === 'multiple') {
                $input = data_get($field, 'options', []);
                if ($min = data_get($field, 'min')) $rules['min:'.$min] = $label.' must have at least '.$min.' '.str('item')->plural($min).'.';
                if ($max = data_get($field, 'max')) $rules['max:'.$max] = $label.' must not have more than '.$max.' '.str('item')->plural($max).'.';
            }
            else if ($input === 'boolean') {
                $input = ['Yes', 'No'];
            }
            else if ($input === 'dropdown') {
                $input = data_get($field, 'options', []);
            }

            return ['customFieldInputs.'.$i.'.value' => [
                'label' => $label,
                'input' => $input,
                'rules' => $rules,
            ]];
        })->all();
    }

    /**
     * Set custom field inputs
     */
    public function setCustomFieldInputs($data)
    {
        if (!$this->customFields) return;

        $data = collect($data);
        $fields = collect($this->customFields)
            ->map(fn($field) => array_merge($field, [
                'value' => data_get(
                    $data->first(fn($val) => data_get($val, 'uuid') === data_get($field, 'uuid')),
                    'value'
                ),
            ]))
            ->concat($data
                ->reject(fn($val) => collect($this->customFields)->pluck('uuid')->contains(data_get($val, 'uuid')))
                ->values()
            )
            ->values();

        $this->fill(['customFieldInputs' => $fields->all()]);
    }
}