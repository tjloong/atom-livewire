<?php

namespace Jiannius\Atom\Http\Livewire\App\Plan;

use Livewire\Component;
use Illuminate\Validation\Rule;
use Jiannius\Atom\Traits\Livewire\WithPopupNotify;

class Form extends Component
{
    use WithPopupNotify;
    
    public $plan;
    public $upgradables;
    public $downgradables;

    /**
     * Validation rules
     */
    protected function rules()
    {
        return [
            'plan.name' => [
                'required',
                Rule::unique('plans', 'name')->ignore($this->plan),
            ],
            'plan.slug' => 'nullable',
            'plan.trial' => 'nullable',
            'plan.excerpt' => 'nullable',
            'plan.features' => 'nullable',
            'plan.payment_description' => 'nullable',
            'plan.cta' => 'nullable',
            'plan.is_active' => 'nullable',
        ];
    }

    /**
     * Validation messages
     */
    protected function messages()
    {
        return [
            'plan.name.required' => 'Plan name is required.',
            'plan.name.unique' => 'There is another plan with the same name.',
        ];
    }

    /**
     * Mount
     */
    public function mount()
    {
        $this->upgradables = $this->plan->upgradables->pluck('id')->toArray();
        $this->downgradables = $this->plan->downgradables->pluck('id')->toArray();
    }

    /**
     * Get other plans property
     */
    public function getOtherPlansProperty()
    {
        return model('plan')
            ->when($this->plan->exists, fn($q) => $q->where('id', '<>', $this->plan->id))
            ->selectRaw('id as value, name as label')
            ->get();
    }

    /**
     * Submit
     */
    public function submit()
    {
        $this->resetValidation();
        $this->validate();

        $this->plan->fill([
            'trial' => $this->plan->trial ?? null,
        ])->save();

        $this->plan->upgradables()->sync($this->upgradables);
        $this->plan->downgradables()->sync($this->downgradables);

        if ($this->plan->wasRecentlyCreated) return redirect()->route('app.plan.update', [$this->plan->id]);

        $this->popup('Plan Updated.');
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.plan.form');
    }
}