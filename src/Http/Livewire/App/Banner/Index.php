<?php

namespace Jiannius\Atom\Http\Livewire\App\Banner;

use Jiannius\Atom\Component;
use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Jiannius\Atom\Traits\Livewire\WithTable;

class Index extends Component
{
    use WithTable;
    use WithPopupNotify;

    public $sort;

    public $filters = [
        'search' => null,
        'status' => null,
    ];

    protected $listeners = [
        'bannerUpdateClosed' => '$refresh',
    ];

    // get query property
    public function getQueryProperty(): mixed
    {
        return model('banner')
            ->filter($this->filters)
            ->when(!$this->sort, fn($q) => $q->oldest('seq')->latest('id'));
    }

    // update
    public function update($id = null): void
    {
        $this->emitTo('app.banner.update', 'open', $id);
    }

    // sort
    public function sort($data): void
    {
        foreach ($data as $seq => $id) {
            model('banner')->find($id)->fill(['seq' => $seq])->save();
        }
    }

    // delete
    public function delete()
    {
        if ($this->checkboxes) {
            model('banner')->whereIn('id', $this->checkboxes)->delete();
            $this->resetCheckboxes();
        }
    }
}