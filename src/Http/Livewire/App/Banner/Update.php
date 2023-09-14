<?php

namespace Jiannius\Atom\Http\Livewire\App\Banner;

use Jiannius\Atom\Component;
use Jiannius\Atom\Traits\Livewire\WithFileInput;
use Jiannius\Atom\Traits\Livewire\WithForm;

class Update extends Component
{
    use WithFileInput;
    use WithForm;

    public $banner;

    protected $listeners = [
        'createBanner' => 'open',
        'updateBanner' => 'open',
    ];

    // validation
    protected function validation() : array
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
            'banner.start_at' => ['nullable'],
            'banner.end_at' => ['nullable'],
        ];
    }

    // get options property
    public function getOptionsProperty() : array
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

    // open
    public function open($id = null) : void
    {
        if ($this->banner = $id
            ? model('banner')->find($id)
            : model('banner')->fill(['is_active' => true])
        ) {
            $this->dispatchBrowserEvent('banner-update-open');
        }
    }

    // close
    public function close() : void
    {
        $this->emit('bannerSaved');
        $this->dispatchBrowserEvent('banner-update-close');
    }

    // delete
    public function delete() : void
    {
        $this->banner->delete();
        $this->close();
    }

    // submit
    public function submit() : void
    {
        $this->validateForm();
        $this->banner->save();
        $this->close();
    }
}