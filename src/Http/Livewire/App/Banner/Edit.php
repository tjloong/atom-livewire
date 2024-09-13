<?php

namespace Jiannius\Atom\Http\Livewire\App\Banner;

use Jiannius\Atom\Component;
use Jiannius\Atom\Traits\Livewire\WithForm;

class Edit extends Component
{
    use WithForm;

    public $banner;

    protected $listeners = [
        'editBanner' => 'open',
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

    // open
    public function open($data = []) : void
    {
        $ulid = get($data, 'ulid');

        if (
            $this->banner = $ulid
            ? model('banner')->where('ulid', $ulid)->first()
            : model('banner')->fill(['is_active' => true, ...$data])
        ) {
            $this->modal(id: 'banner-update');
        }
    }

    // delete
    public function delete() : void
    {
        $this->banner->delete();
        $this->overlay(false);
    }

    // submit
    public function submit() : void
    {
        $this->validateForm();
        $this->banner->save();
        $this->overlay(false);
    }
}