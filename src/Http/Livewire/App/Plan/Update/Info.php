<?php

namespace Jiannius\Atom\Http\Livewire\App\Plan\Update;

use Livewire\Component;
use Illuminate\Validation\Rule;
use Jiannius\Atom\Traits\Livewire\WithPopupNotify;

class Info extends Component
{
    use WithPopupNotify;
    
    public $plan;
    public $features;
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
            'plan.name.required' => __('Plan name is required.'),
            'plan.name.unique' => __('There is another plan with the same name.'), 
        ];
    }

    /**
     * Mount
     */
    public function mount()
    {
        $this->setFeatures();
        $this->upgradables = $this->plan->upgradables->pluck('id')->toArray();
        $this->downgradables = $this->plan->downgradables->pluck('id')->toArray();
    }

    /**
     * Get options property
     */
    public function getOptionsProperty()
    {
        $otherplans = model('plan')
            ->when($this->plan->exists, fn($q) => $q->where('id', '<>', $this->plan->id))
            ->selectRaw('id as value, name as label');

        return [
            'upgradables' => $otherplans->whereNotIn('id', $this->upgradables)->get(),
            'downgradables' => $otherplans->whereNotIn('id', $this->downgradables)->get(),
        ];
    }

    /**
     * Set features
     */
    public function setFeatures()
    {
        $this->features = collect($this->plan->features ?? [])->filter()->join("\n");
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
            'features' => $this->features 
                ? collect(explode("\n", $this->features))->filter()->map(fn($val) => trim($val))->values()->all()
                : null,
        ])->save();

        $this->plan->upgradables()->sync($this->upgradables);
        $this->plan->downgradables()->sync($this->downgradables);

        $this->setFeatures();

        if ($this->plan->wasRecentlyCreated) return redirect()->route('app.settings', ['plans']);
        else $this->popup('Plan Updated.');
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.plan.update.info');
    }
}