<?php

namespace Jiannius\Atom\Http\Livewire\App\Banner;

use Jiannius\Atom\Component;
use Jiannius\Atom\Traits\Livewire\WithForm;

class Update extends Component
{
    use WithForm;

    public $banner;

    protected $listeners = [
        'createBanner' => 'create',
        'updateBanner' => 'update',
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

    // create
    public function create() : void
    {
        $this->banner = model('banner')->fill(['is_active' => true]);
        $this->open();
    }

    // update
    public function update($id) : void
    {
        $this->banner = model('banner')->find($id);
        $this->open();
    }

    // open
    public function open() : void
    {
        if ($this->banner) {
            $this->modal(id: 'banner-update');
        }
    }

    // close
    public function close() : void
    {
        $this->emit('setBannerId');
        $this->modal(false, 'banner-update');
    }

    // delete
    public function delete() : void
    {
        $this->banner->delete();
        $this->emit('bannerDeleted');
        $this->close();
    }

    // submit
    public function submit() : void
    {
        $this->validateForm();

        $this->banner->save();

        if ($this->banner->wasRecentlyCreated) $this->emit('bannerCreated');
        else $this->emit('bannerUpdated');

        $this->close();
    }
}