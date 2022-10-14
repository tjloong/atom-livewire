<?php

namespace Jiannius\Atom\Http\Livewire\App\Settings\Website;

use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class Announcement extends Component
{
    use WithPopupNotify;

    public $input;
    public $announcements;

    /**
     * Validation rules
     */
    protected function rules()
    {
        return [
            'input.type' => 'required',
            'input.content' => 'required',
            'input.is_active' => 'nullable',
        ];
    }

    /**
     * Validation messages
     */
    protected function messages()
    {
        return [
            'input.type.required' => __('Announcement type is required.'),
            'input.content.required' => __('Announcement content is required.'),
        ];
    }

    /**
     * Mount
     */
    public function mount()
    {
        $this->getAnnouncements();
    }

    /**
     * Get types property
     */
    public function getTypesProperty()
    {
        return [
            ['value' => 'general', 'label' => 'General'],
        ];
    }

    /**
     * Get announcements
     */
    public function getAnnouncements()
    {
        $this->announcements = collect(json_decode(site_settings('announcements')));
    }

    /**
     * Open
     */
    public function open($uuid = null)
    {
        $this->input = $uuid
            ? $this->announcements->firstWhere('uuid', $uuid)
            : [
                'uuid' => null,
                'url' => null,
                'type' => data_get(head($this->types), 'value'),
                'content' => null,
                'is_active' => false,    
            ];

        $this->dispatchBrowserEvent('announcement-form-modal-open');
    }

    /**
     * Sort
     */
    public function sort($data)
    {
        $this->announcements = collect($data)->map(fn($uuid) => $this->announcements->firstWhere('uuid', $uuid));

        $this->persist();
        $this->popup('Announcements Sorted.');
    }

    /**
     * Submit
     */
    public function submit()
    {
        $this->resetValidation();
        $this->validate();

        $uuid = data_get($this->input, 'uuid');
        $index = $uuid ? $this->announcements->search(fn($val) => data_get($val, 'uuid') === $uuid) : null;

        if (is_numeric($index)) $this->announcements->put($index, $this->input);
        else $this->announcements->push(array_merge($this->input, ['uuid' => str()->uuid()]));

        $this->persist();
        $this->popup('Announcement Updated');
        $this->dispatchBrowserEvent('announcement-form-modal-close');
    }

    /**
     * Delete
     */
    public function delete($uuid)
    {
        $this->announcements = $this->announcements
            ->reject(fn($val) => data_get($val, 'uuid') === $uuid)
            ->values();

        $this->persist();
        $this->popup('Announcement Deleted.');
    }

    /**
     * Persist
     */
    public function persist()
    {
        site_settings(['announcements' => $this->announcements]);
        $this->getAnnouncements();
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.settings.website.announcement');
    }
}