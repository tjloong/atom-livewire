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

            'inputs.features' => ['nullable'],
        ];
    }

    /**
     * Mount
     */
    public function mount()
    {
        $this->fill(['inputs.features' => implode("\n", $this->plan->features)]);
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
    public function submit(): mixed
    {
        $this->validateForm();

        $this->plan->fill([
            'features' => data_get($this->inputs, 'features'),
        ])->save();

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