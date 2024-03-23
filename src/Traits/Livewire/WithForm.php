<?php

namespace Jiannius\Atom\Traits\Livewire;

use Illuminate\Support\Facades\Http;
use RuntimeException;

trait WithForm
{
    public $form = [
        'required' => [],
        'recaptcha_token' => null,
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
    public function validateForm($attr = [], $config = []) : void
    {
        if (data_get($this->form, 'recaptcha_token')) {
            $this->validate([
                'form.recaptcha_token' => function($attr, $value, $fail) {
                    try {
                        $api = env('RECAPTCHA_API_ENDPOINT');
                        $min = env('RECAPTCHA_MIN_SCORE');

                        $res = Http::asForm()->post(
                            empty($api) ? 'https://www.google.com/recaptcha/api/siteverify' : $api,
                            [
                                'secret' => settings('recaptcha_secret_key'),
                                'response' => $value,
                                'remoteip' => request()->ip(),
                            ],
                        )->throw()->json();

                        $this->resetRecaptchaToken();

                        throw_if(
                            !data_get($res, 'success')
                            || data_get($res, 'score') < (empty($min) ? 0.5 : $min)
                        );
                    }
                    catch (RuntimeException $e) {
                        $e = tr('common.alert.recaptcha');
                        $this->popup($e, 'alert', 'error');
                        $fail($e);
                    }
                },
            ]);
        }

        $this->resetValidation();

        if ($attr) $this->validate($attr);
        else $this->validate();

        // auto trim values
        $except = (array)data_get($config, 'trim_except');
        
        collect(array_keys($this->rules()))
            ->reject(fn($key) => in_array($key, $except))
            ->each(function($key) {
                $value = data_get($this, $key);
                if (is_string($value)) data_set($this, $key, trim($value));
            });
    }

    // reset recaptcha token
    public function resetRecaptchaToken() : void
    {
        $this->fill(['form.recaptcha_token' => null]);
    }
}