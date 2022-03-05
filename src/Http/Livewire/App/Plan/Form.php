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

    protected $messages = [
        'plan.name.required' => 'Plan name is required.',
        'plan.name.unique' => 'There is another plan with the same name.',
    ];

    protected function rules()
    {
        return [
            'plan.name' => [
                'required',
                Rule::unique('plans', 'name')->ignore($this->plan),
            ],
            'plan.slug' => 'nullable',
            'plan.excerpt' => 'nullable',
            'plan.cta' => 'nullable',
            'plan.is_active' => 'nullable',
        ];
    }

    /**
     * Mount
     */
    public function mount()
    {
        $this->features = $this->getFeatures();
        $this->upgradables = $this->getUpgradables();
        $this->downgradables = $this->getDowngradables();
    }

    /**
     * Get other plans property
     */
    public function getOtherPlansProperty()
    {
        return model('plan')
            ->when($this->plan->exists, fn($q) => $q->where('id', '<>', $this->plan->id))
            ->get()
            ->map(fn($plan) => ['value' => $plan->id, 'label' => $plan->name]);
    }
    
    /**
     * Get features
     */
    public function getFeatures()
    {
        return collect($this->plan->features ?? [])->filter()->join("\n");
    }

    /**
     * Get upgradables
     */
    public function getUpgradables()
    {
        return $this->plan->upgradables->pluck('id')->toArray();
    }

    /**
     * Get downgradables
     */
    public function getDowngradables()
    {
        return $this->plan->downgradables->pluck('id')->toArray();
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

        $this->plan->save();
        $this->plan->upgradables()->sync($this->upgradables);
        $this->plan->downgradables()->sync($this->downgradables);

        $this->features = $this->getFeatures();
        $this->upgradables = $this->getUpgradables();
        $this->downgradables = $this->getDowngradables();

        $this->emitUp('saved', $this->plan->id);
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.plan.form', [
            'otherPlans' => $this->otherPlans,
        ]);
    }
}