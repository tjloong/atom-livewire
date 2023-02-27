<?php

namespace Jiannius\Atom\Http\Livewire\App\Promotion;

use Livewire\Component;

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
                function ($attr, $value, $fail) {
                    if (model('promotion')->readable()->where('code', $value)->where('id', '<>', $this->promotion->id)->count()) {
                        $fail('This promotion code is taken.');
                    }
                },
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
            'promotion.name.required' => 'Promotion name is required.',
            'promotion.code.unique' => 'This promotion code is taken.',
            'promotion.type.required' => 'Discount type is required.',
            'promotion.rate.required' => 'Discount rate is required.',
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
        return atom_view('app.promotion.form');
    }
}
