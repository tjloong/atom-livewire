<?php

namespace Jiannius\Atom\Http\Livewire\App\Settings\Integration;

use Jiannius\Atom\Atom;
use Jiannius\Atom\Component;
use Jiannius\Atom\Traits\Livewire\WithForm;

class Storage extends Component
{
    use WithForm;

    public $settings;

    // validation
    protected function validation(): array
    {
        return [
            'settings.filesystem' => ['required' => 'Storage provider is required'],
            'settings.do_spaces_key' => ['required_if:settings.filesystem,do' => 'DO spaces key is required.'],
            'settings.do_spaces_secret' => ['required_if:settings.filesystem,do' => 'DO spaces secret is required.'],
            'settings.do_spaces_region' => ['required_if:settings.filesystem,do' => 'DO spaces region is required.'],
            'settings.do_spaces_bucket' => ['required_if:settings.filesystem,do' => 'DO spaces bucket is required.'],
            'settings.do_spaces_endpoint' => ['required_if:settings.filesystem,do' => 'DO spaces endpoint is required.'],
            'settings.do_spaces_folder' => ['required_if:settings.filesystem,do' => 'DO spaces folder is required.'],
        ];
    }

    // mount
    public function mount(): void
    {
        foreach ([
            'filesystem',
            'do_spaces_key',
            'do_spaces_secret',
            'do_spaces_region',
            'do_spaces_bucket',
            'do_spaces_endpoint',
            'do_spaces_folder',
        ] as $key) {
            $this->fill(['settings.'.$key => settings($key)]);
        }
    }

    // submit
    public function submit(): void
    {
        $this->validateForm();

        settings($this->settings);

        Atom::toast('updated', 'success');
    }
}