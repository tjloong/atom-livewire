<?php

namespace Jiannius\Atom\Http\Livewire\App\Settings\Website;

use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class Announcement extends Component
{
    use WithPopupNotify;

    public $announcements;

    protected $listeners = [
        'setAnnouncement',
        'refresh' => '$refresh',
    ];

    /**
     * Mount
     */
    public function mount(): void
    {
        $this->announcements = settings('announcements');
    }

    /**
     * Set announcement
     */
    public function setAnnouncement($data): void
    {
        $announcements = collect($this->announcements);
        $index = $announcements->where('uuid', data_get($data, 'uuid'))->keys()->first();

        if (is_numeric($index)) $announcements->put($index, $data);
        else $announcements->push($data);

        $this->announcements = $announcements->values()->all();

        settings(['announcements' => $this->announcements]);

        $this->emit('refresh');
    }

    /**
     * Sort
     */
    public function sort($data): void
    {
        $this->announcements = collect($data)
            ->map(fn($uuid) => collect($this->announcements)->firstWhere('uuid', $uuid))
            ->values()
            ->all();

        settings(['announcements' => $this->announcements]);

        $this->popup('Announcements Sorted.');
    }

    /**
     * Delete
     */
    public function delete($uuid): void
    {
        $this->announcements = collect($this->announcements)
            ->reject(fn($val) => data_get($val, 'uuid') === $uuid)
            ->values()
            ->all();

        settings(['announcements' => $this->announcements]);

        $this->popup('Announcement Deleted.');
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.settings.website.announcement');
    }
}