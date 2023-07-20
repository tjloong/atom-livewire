<?php

namespace Jiannius\Atom\Http\Livewire\App\Settings\File;

use Jiannius\Atom\Component;
use Jiannius\Atom\Traits\Livewire\WithFile;
use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Jiannius\Atom\Traits\Livewire\WithTable;

class Index extends Component
{
    use WithFile;
    use WithPopupNotify;
    use WithTable;

    public $sort;

    public $filters = [
        'mime' => null,
        'search' => null,
    ];

    protected $listeners = ['refresh' => '$refresh'];

    // get query property
    public function getQueryProperty(): mixed
    {
        return model('file')
            ->readable()
            ->filter($this->filters)
            ->when(!$this->sort, fn($q) => $q->latest('updated_at'));
    }

    // delete
    public function delete(): void
    {
        if ($this->checkboxes) {
            model('file')->whereIn('id', $this->checkboxes)->get()->each(fn($q) => $q->delete());

            $this->popup(count($this->checkboxes).' Files Deleted');
            $this->resetCheckboxes();
        }
    }
}