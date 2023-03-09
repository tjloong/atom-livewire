<?php

namespace Jiannius\Atom\Traits\Livewire;

trait WithForm
{
    public $form = [
        'required' => [],
    ];

    /**
     * Validation rules
     */
    protected function rules()
    {
        return collect($this->validation())->mapWithKeys(fn($props, $field) => [
            $field => collect($props)
                ->map(fn($val, $key) => is_string($key) ? $key : $val)
                ->values()
                ->all() ?: ['nullable'],
        ])->toArray();
    }

    /**
     * Validation messages
     */
    protected function messages()
    {
        $messages = [];

        collect($this->validation())->each(function($rules, $field) use (&$messages) {
            foreach ((array)$rules as $rule => $message) {
                if (is_string($rule) && $rule !== 'nullable') {
                    if (str($rule)->is('*:*')) $rule = head(explode(':', $rule));
                    $messages[$field.'.'.$rule] = $message;
                }
            }
        });

        return $messages;
    }

    /**
     * Mount
     */
    public function mountWithForm()
    {
        $this->fill([
            'form.required' => collect($this->rules())
                ->mapWithKeys(fn($rules, $key) => [$key => in_array('required', $rules)])
                ->filter(fn($val) => $val === true)
                ->all(),
        ]);
    }

    /**
     * Validate form
     */
    public function validateForm()
    {
        $this->resetValidation();
        $this->validate();
    }
}