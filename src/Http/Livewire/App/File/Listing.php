<?php

namespace Jiannius\Atom\Http\Livewire\App\File;

use Illuminate\Database\Eloquent\Builder;
use Jiannius\Atom\Traits\Livewire\WithFile;
use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Jiannius\Atom\Traits\Livewire\WithTable;
use Livewire\Component;

class Listing extends Component
{
    use WithFile;
    use WithPopupNotify;
    use WithTable;

    public $sort = 'updated_at,desc';
    public $filters = [
        'mime' => null,
        'search' => null,
    ];

    protected $listeners = ['refresh' => '$refresh'];

    /**
     * Get query property
     */
    public function getQueryProperty(): Builder
    {
        return model('file')->readable()->filter($this->filters);
    }

    /**
     * Get options property
     */
    public function getOptionsProperty(): array
    {
        return [
            'mime' => [
                ['value' => 'image/*', 'label' => 'Image'],
                ['value' => 'video/*', 'label' => 'Video'],
                ['value' => 'audio/*', 'label' => 'Audio'],
                ['value' => 'file', 'label' => 'File'],
                ['value' => 'youtube', 'label' => 'Youtube'],
            ],
        ];
    }

    /**
     * Delete
     */
    public function delete(): void
    {
        if ($this->checkboxes) {
            model('file')->whereIn('id', $this->checkboxes)->get()->each(fn($q) => $q->delete());

            $this->popup(count($this->checkboxes).' Files Deleted');
            $this->resetCheckboxes();
        }
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.file.listing');
    }
}