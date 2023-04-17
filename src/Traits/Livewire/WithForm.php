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
                ->mapWithKeys(fn($rules, $key) => [
                    $key => collect($rules)
                        ->filter(fn($val) => is_string($val) && str($val)->startsWith('required'))
                        ->count() > 0,
                ])
                ->filter(fn($val) => $val === true)
                ->all(),
        ]);
    }

    /**
     * Validate form
     */
    public function validateForm($config = [])
    {
        $this->resetValidation();
        $this->validate();

        // auto trim values
        $except = (array)data_get($config, 'trim_except');
        
        collect(array_keys($this->rules()))
            ->reject(fn($key) => in_array($key, $except))
            ->each(function($key) {
                $value = data_get($this, $key);
                if (is_string($value)) data_set($this, $key, trim($value));
            });
    }
}