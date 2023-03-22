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
            'plan.name' => [
                'required' => 'Plan name is required.',
                function ($attr, $value, $fail) {
                    if (model('plan')->where('name', $value)->where('id', '<>', $this->plan->id)->count()) {
                        $fail('Plan name is taken.');
                    }
                },
            ],
            'plan.slug' => ['nullable'],
            'plan.trial' => ['nullable'],
            'plan.excerpt' => ['nullable'],
            'plan.features' => ['nullable'],
            'plan.payment_description' => ['nullable'],
            'plan.cta' => ['nullable'],
            'plan.is_active' => ['nullable'],
        ];
    }

    /**
     * Mount
     */
    public function mount(): void
    {
        $this->fill([
            'inputs.upgradables' => $this->plan->upgradables->pluck('id')->toArray(),
            'inputs.downgradables' => $this->plan->downgradables->pluck('id')->toArray(),
        ]);
    }

    /**
     * Get options property
     */
    public function getOptionsProperty(): array
    {
        return [
            'plans' => model('plan')
                ->when($this->plan->exists, fn($q) => $q->where('id', '<>', $this->plan->id))
                ->selectRaw('id as value, name as label')
                ->get()
                ->toArray(),
        ];
    }

    /**
     * Submit
     */
    public function submit(): mixed
    {
        $this->validateForm();

        $this->plan->fill([
            'trial' => $this->plan->trial ?? null,
        ])->save();

        $this->plan->upgradables()->sync(data_get($this->inputs, 'upgradables'));
        $this->plan->downgradables()->sync(data_get($this->inputs, 'downgradables'));

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