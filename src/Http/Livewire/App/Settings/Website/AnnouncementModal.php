<?php

namespace Jiannius\Atom\Http\Livewire\App\Settings\Website;

use Jiannius\Atom\Traits\Livewire\WithForm;
use Livewire\Component;

class AnnouncementModal extends Component
{
    use WithForm;

    public $inputs;

    protected $listeners = ['open'];

    /**
     * Validation
     */
    protected function validation(): array
    {
        return [
            'inputs.title' => ['required' => 'Title is required.'],
            'inputs.type' => ['required' => 'Type is required.'],
            'inputs.category' => ['required' => 'Category is required.'],
            'inputs.content' => ['nullable'],
            'inputs.href' => ['nullable'],
            'inputs.cta' => ['nullable'],
            'inputs.is_active' => ['nullable'],
        ];
    }

    /**
     * Get options property
     */
    public function getOptionsProperty(): array
    {
        return [
            'types' => [
                ['value' => 'static', 'label' => 'Static'],
                ['value' => 'link', 'label' => 'External Link'],
                ['value' => 'popup', 'label' => 'Pop-Up'],
            ],
            'categories' => [
                ['value' => 'general', 'label' => 'General'],
            ],
        ];
    }

    /**
     * Open
     */
    public function open($data = null): void
    {
        $this->inputs = $data ?? [
            'uuid' => str()->uuid(),
            'title' => null,
            'type' => data_get(head(data_get($this->options, 'types')), 'value'),
            'category' => data_get(head(data_get($this->options, 'categories')), 'value'),
            'content' => null,
            'href' => null,
            'cta' => null,
            'is_active' => true,
        ];

        $this->dispatchBrowserEvent('announcement-modal-open');
    }

    /**
     * Submit
     */
    public function submit(): void
    {
        $this->validateForm();

        $this->emit('setAnnouncement', $this->inputs);
        $this->dispatchBrowserEvent('announcement-modal-close');
        $this->fill(['inputs' => []]);
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.settings.website.announcement-modal');
    }
}