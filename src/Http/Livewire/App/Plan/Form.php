<?php

namespace Jiannius\Atom\Http\Livewire\App\Plan;

use Jiannius\Atom\Traits\Livewire\WithForm;
use Livewire\Component;

class Form extends Component
{
    use WithForm;
    
    public $plan;
    public $inputs;

    protected $listeners = ['refresh' => '$refresh'];

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
            'plan.country' => ['nullable'],
            'plan.currency' => ['nullable'],
            'plan.trial' => ['nullable'],
            'plan.is_active' => ['nullable'],
        ];
    }

    /**
     * Mount
     */
    public function mount(): void
    {
        $this->fill([
            'inputs.features' => implode("\n", $this->plan->features),
            'inputs.upgrades' => $this->plan->upgrades->pluck('id')->toArray(),
        ]);
    }

    /**
     * Get options property
     */
    public function getOptionsProperty(): array
    {
        return [
            'upgrades' => model('plan')
                ->status('active')
                ->where('id', '<>', $this->plan->id)
                ->get()
                ->map(fn($plan) => [
                    'value' => $plan->id,
                    'label' => $plan->code,
                    'small' => $plan->name,
                ])
                ->toArray(),
        ];
    }

    /**
     * Updated plan
     */
    public function updatedPlan()
    {
        if ($this->plan->name && !$this->plan->code) {
            $this->plan->fill(['code' => str()->slug($this->plan->name)]);
        }
    }

    /**
     * Submit
     */
    public function submit(): void
    {
        $this->validateForm();

        $this->plan->fill([
            'features' => data_get($this->inputs, 'features'),
        ])->save();

        $this->plan->upgrades()->sync(data_get($this->inputs, 'upgrades'));

        $this->emit('submitted', $this->plan->id);
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.plan.form');
    }
}