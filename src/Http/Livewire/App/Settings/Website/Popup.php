<?php

namespace Jiannius\Atom\Http\Livewire\App\Settings\Website;

use Jiannius\Atom\Traits\Livewire\WithForm;
use Livewire\Component;

class Popup extends Component
{
    use WithForm;

    public $popup;

    /**
     * Validation
     */
    protected function validation(): array
    {
        return [
            'popup.content' => ['required' => 'Content is required.'],
            'popup.delay' => ['required' => 'Delay is required.'],
        ];
    }

    /**
     * Mount
     */
    public function mount(): void
    {
        $this->popup = settings('popup');
    }

    /**
     * Submit
     */
    public function submit(): void
    {
        $this->validateForm();

        settings(['popup' => $this->popup]);

        $this->popup('Pop-Up Updated.');
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.settings.website.popup');
    }
}