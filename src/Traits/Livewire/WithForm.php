<?php

namespace Jiannius\Atom\Traits\Livewire;

trait WithForm
{
    public $form = [
        'required' => [],
    ];

    // validation rules
    protected function rules() : array
    {
        return collect($this->validation())->mapWithKeys(fn($props, $field) => [
            $field => collect($props)
                ->map(fn($val, $key) => is_string($key) ? $key : $val)
                ->values()
                ->all() ?: ['nullable'],
        ])->toArray();
    }

    // validation messages
    protected function messages() : array
    {
        $messages = [];

        collect($this->validation())->each(function($rules, $field) use (&$messages) {
            foreach ((array)$rules as $rule => $message) {
                if (is_string($rule) && $rule !== 'nullable') {
                    if (str($rule)->is('*:*')) $rule = head(explode(':', $rule));
                    $messages[$field.'.'.$rule] = $message;
                }
            }
        })->toArray();

        return $messages;
    }

    // mount
    public function mountWithForm() : void
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

    // perform form validation
    public function validateForm($attr = []) : void
    {
        $this->resetValidation();

        if ($attr) $this->validate($attr);
        else $this->validate();
    }
}