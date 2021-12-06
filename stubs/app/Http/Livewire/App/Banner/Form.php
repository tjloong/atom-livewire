<?php

namespace App\Http\Livewire\App\Banner;

use Livewire\Component;
use Illuminate\Support\Facades\Validator;

class Form extends Component
{
    public $banner;

    protected $rules = [
        'banner.name' => 'required',
        'banner.type' => 'required',
        'banner.url' => 'nullable',
        'banner.is_active' => 'nullable',
        'banner.start_at' => 'nullable',
        'banner.end_at' => 'nullable',
        'banner.image_id' => 'nullable',
    ];

    /**
     * Mount event
     * 
     * @return void
     */
    public function mount($banner)
    {
        $this->banner = $banner;
    }

    /**
     * Rendering livewire view
     * 
     * @return Response
     */
    public function render()
    {
        return view('livewire.app.banner.form');
    }

    /**
     * Handle save
     * 
     * @return void
     */
    public function save()
    {
        $this->validateinputs();
        $this->banner->save();
        $this->emitUp('saved', $this->banner->id);
    }

    /**
     * Validate inputs
     * 
     * @return void
     */
    public function validateinputs()
    {
        $this->resetValidation();

        $validator = Validator::make(
            ['banner' => $this->banner],
            $this->rules,
            [
                'banner.name.required' => 'Banner name is required.',
                'banner.type.required' => 'Banner type is required.',
            ]
        );

        if ($validator->fails()) {
            $this->dispatchBrowserEvent('toast', 'formError');
            $validator->validate();
        }
    }
}