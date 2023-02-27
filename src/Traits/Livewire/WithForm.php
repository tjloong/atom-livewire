<?php

namespace Jiannius\Atom\Traits\Livewire;

trait WithForm
{
    /**
     * Validation rules
     */
    public function rules()
    {
        return collect($this->form())->collapse()
            ->filter(fn($prop, $field) => !str($field)->is('__*') && $field !== 'slot')
            ->mapWithKeys(function ($prop, $field) {
                $rules = collect(data_get($prop, 'rules'))->map(fn($val, $key) => 
                    is_string($key) ? $key : $val,
                )->values()->all();

                return [$field => $rules ?: ['nullable']];
            })
            ->all();
    }

    /**
     * Validation messages
     */
    public function messages()
    {
        $messages = [];

        collect($this->form())->collapse()
            ->filter(fn($prop, $field) => !str($field)->is('__*') && $field !== 'slot')
            ->each(function($prop, $field) use (&$messages) {
                if ($rules = data_get($prop, 'rules')) {
                    foreach ($rules as $rule => $message) {
                        if (is_string($rule) && $rule !== 'nullable') {
                            $messages[$field.'.'.$rule] = $message;
                        }
                    }
                }
            });

        return $messages;
    }

    /**
     * Get form property
     */
    public function getFormProperty()
    {
        return $this->form();
    }
}