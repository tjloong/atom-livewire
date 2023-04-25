<?php

namespace Jiannius\Atom\Http\Livewire\App\Plan;

use Jiannius\Atom\Traits\Livewire\WithForm;
use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class Form extends Component
{
    use WithForm;
    use WithPopupNotify;
    
    public $plan;
    public $inputs;

    /**
     * Validation
     */
    protected function validation(): array
    {
        return [
            'plan.name' => ['required' => 'Plan name is required.'],
            'plan.code' => [
                'required' => 'Plan code is required.',
                function ($attr, $value, $fail) {
                    if (model('plan')->where('code', $value)->where('id', '<>', $this->plan->id)->count()) {
                        $fail('Plan code is taken.');
                    }
                },
            ],
            'plan.description' => ['nullable'],
            'plan.features' => ['nullable'],
            'plan.invoice_description' => ['nullable'],
            'plan.country' => ['nullable'],
            'plan.currency' => ['nullable'],
            'plan.price' => ['nullable'],
            'plan.valid' => [
                'nullable',
                function ($attr, $value, $fail) {
                    if ($this->plan->getEndDate(now()) === false) $fail('Invalid valid period.');
                },
            ],
            'plan.trial_plan_id' => ['nullable'],
            'plan.is_recurring' => ['nullable'],
            'plan.is_hidden' => ['nullable'],
            'plan.is_active' => ['nullable'],
        ];
    }

    /**
     * Get options property
     */
    public function getOptionsProperty()
    {
        return [
            'trial_plans' => model('plan')->where('id', '<>', $this->plan->id)->get()->map(fn($plan) => [
                'value' => $plan->id,
                'label' => $plan->code,
                'small' => $plan->name,
            ])->toArray(),
        ];
    }

    /**
     * Submit
     */
    public function submit(): mixed
    {
        $this->validateForm();

        $this->plan->save();

        return $this->plan->wasRecentlyCreated
            ? redirect()->route('app.plan.update', [$this->plan->id])
            : $this->popup('Plan Updated.');
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.plan.form');
    }
}