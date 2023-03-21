<?php

namespace Jiannius\Atom\Traits\Livewire;

trait WithCustomFields
{
    /**
     * Get custom fields property
     */
    public function getCustomFieldsProperty()
    {
        return [];
    }

    /**
     * Validate custom fields
     */
    public function validateCustomFields()
    {
        $rules = [];
        $messages = [];

        foreach ($this->customFields as $i => $field) {
            $attr = 'inputs.custom.'.$i.'.value';
            $rules[$attr] = [];

            $type = data_get($field, 'type');
            $label = data_get($field, 'label');

            if (data_get($field, 'required')) {
                $rules[$attr][] = 'required';
                $messages[$attr.'.required'] = $label.' is required.';
            }

            if ($type === 'multiple') {
                if ($min = data_get($field, 'min')) {
                    $rules[$attr][] = 'min:'.$min;
                    $messages[$attr.'.min'] = $label.' must have at least '.$min.' '.str('item')->plural($min).'.';
                }

                if ($max = data_get($field, 'max')) {
                    $rules[$attr][] = 'max:'.$max;
                    $messages[$attr.'.max'] = $label.' must not have more than '.$max.' '.str('item')->plural($max).'.';
                }
            }
        }

        $this->validate($rules, $messages);
    }
}