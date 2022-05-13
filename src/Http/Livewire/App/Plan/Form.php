<?php

namespace Jiannius\Atom\Http\Livewire\App\Plan;

use Livewire\Component;
use Illuminate\Validation\Rule;

class Form extends Component
{
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

        if ($this->features) {
            $this->plan->features = collect(explode("\n", $this->features))
                ->filter()
                ->map(fn($val) => trim($val))
                ->values()
                ->all();
        }

        $this->plan->trial = $this->plan->trial ?? null;
        $this->plan->save();
        $this->plan->upgradables()->sync($this->upgradables);
        $this->plan->downgradables()->sync($this->downgradables);

        $this->setFeatures();

        $this->emitUp('saved', $this->plan->id);
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.plan.form');
    }
}