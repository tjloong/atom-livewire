<?php

namespace Jiannius\Atom\Http\Livewire\App\Promotion;

use Livewire\Component;
use Illuminate\Validation\Rule;

class Form extends Component
{
    public $promotion;

    /**
     * Validation rules
     */
    public function rules()
    {
        return [
            'promotion.name' => 'required',
            'promotion.code' => [
                'nullable',
                model('promotion')->enabledBelongsToAccountTrait
                ? Rule::unique('promotions', 'code')->ignore($this->promotion)->where(fn($q) => $q->where('id', $this->promotion->account_id))
                : Rule::unique('promotions', 'code')->ignore($this->promotion),
            ],
            'promotion.type' => 'required',
            'promotion.rate' => 'required',
            'promotion.usable_limit' => 'nullable',
            'promotion.end_at' => 'nullable',
            'promotion.description' => 'nullable',
            'promotion.is_active' => 'nullable',
        ];
    }

    /**
     * Validation messages
     */
    public function messages()
    {
        return [
            'promotion.name.required' => __('Promotion name is required.'),
            'promotion.code.unique' => __('This promotion code is taken.'),
            'promotion.type.required' => __('Discount type is required.'),
            'promotion.rate.required' => __('Discount rate is required.'),
        ];
    }

    /**
     * Mount
     */
    public function mount()
    {
        //
    }

    /**
     * Submit
     */
    public function submit()
    {
        $this->resetValidation();
        $this->validate();

        $this->promotion->save();

        $this->emitUp('saved', $this->promotion->id);
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.promotion.form');
    }
}
