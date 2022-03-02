<?php

namespace Jiannius\Atom\Http\Livewire\App\Plan;

use Livewire\Component;
use Illuminate\Validation\Rule;

class Form extends Component
{
    public $plan;
    public $features;

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
            'plan.data' => 'nullable',
            'plan.data.cta' => 'nullable',
            'plan.is_active' => 'nullable',
        ];
    }

    /**
     * Mount
     */
    public function mount()
    {
        $this->features = $this->getFeatures();
    }
    
    /**
     * Get features
     */
    public function getFeatures()
    {
        return collect($this->plan->features ?? [])->join("\n");
    }

    /**
     * Submit
     */
    public function submit()
    {
        $this->resetValidation();
        $this->validate();

        if ($this->features) $this->plan->features = collect(explode("\n", $this->features))->map(fn($val) => trim($val))->values()->all();

        $this->plan->save();
        $this->features = $this->getFeatures();

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