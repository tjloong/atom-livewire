<?php

namespace Jiannius\Atom\Http\Livewire\App\Banner;

use Jiannius\Atom\Component;
use Jiannius\Atom\Traits\Livewire\WithTable;

class Listing extends Component
{
    use WithTable;

    public $filters = [
        'search' => null,
        'status' => null,
    ];

    protected $listeners = [
        'bannerCreated' => '$refresh',
        'bannerUpdated' => '$refresh',
        'bannerDeleted' => '$refresh',
    ];

    // get query property
    public function getQueryProperty() : mixed
    {
        return model('banner')
            ->filter($this->filters)
            ->when(!$this->tableOrderBy, fn($q) => $q->oldest('seq')->latest('id'));
    }

    // sort
    public function sort($data) : void
    {
        foreach ($data as $seq => $id) {
            model('banner')->find($id)->fill(['seq' => $seq])->save();
        }

        $this->popup('common.alert.sorted');
    }

    // delete
    public function delete() : void
    {
        if ($this->checkboxes) {
            model('banner')->whereIn('id', $this->checkboxes)->delete();
            $this->reset('checkboxes');
        }
    }
}