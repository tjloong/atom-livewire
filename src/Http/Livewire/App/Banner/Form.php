<?php

namespace Jiannius\Atom\Http\Livewire\App\Banner;

use Jiannius\Atom\Traits\Livewire\WithFile;
use Jiannius\Atom\Traits\Livewire\WithForm;
use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class Form extends Component
{
    use WithFile;
    use WithForm;
    use WithPopupNotify;

    public $banner;

    /**
     * Validation
     */
    protected function validation(): array
    {
        return [
            'banner.type' => ['required' => 'Banner type is required.'],
            'banner.name' => ['required' => 'Banner name is required.'],
            'banner.slug' => [
                'nullable',
                function ($attr, $value, $fail) {
                    if (model('banner')->where('slug', $value)->where('id', '<>', $this->banner->id)->count()) {
                        $fail('Banner slug is taken.');
                    }
                },
            ],
            'banner.url' => ['nullable'],
            'banner.description' => ['nullable'],
            'banner.placement' => ['nullable'],
            'banner.is_active' => ['nullable'],
            'banner.image_id' => ['required' => 'Image is required.'],
        ];
    }

    /**
     * Get options property
     */
    public function getOptionsProperty(): array
    {
        return [
            'types' => collect([
                'main-banner',
            ])->map(fn($val) => [
                'value' => $val,
                'label' => str($val)->headline()->toString(),
            ])->toArray(),

            'placements' => collect([
                'home',
            ])->map(fn($val) => [
                'value' => $val,
                'label' => str($val)->headline()->toString(),
            ])->toArray(),
        ];
    }

    /**
     * Submit
     */
    public function submit(): void
    {
        $this->validateForm();

        $this->banner->save();

        $this->emit('submitted');
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.banner.form');
    }
}