<?php

namespace Jiannius\Atom\Http\Livewire\App\Settings\Integration;

use Jiannius\Atom\Traits\Livewire\WithForm;
use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class Storage extends Component
{
    use WithForm;
    use WithPopupNotify;
    
    public $settings;

    /**
     * Validation
     */
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

    /**
     * Mount
     */
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

    /**
     * Submit
     */
    public function submit(): void
    {
        $this->validateForm();

        settings($this->settings);

        $this->popup('Storage Integration Updated.');
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.settings.integration.storage');
    }
}